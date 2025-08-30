<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Config; // <-- Tambahkan ini
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // =================================================================
        // 1. PERSIAPAN DATA UNTUK DROPDOWN FILTER
        // =================================================================
        // Ambil semua kategori unik yang sudah ada di database, urutkan, dan filter nilai kosong.
        $categories = Archive::whereNotNull('category')
                     ->orderBy('category')
                     ->distinct()
                     ->pluck('category');
        // Ambil hanya user yang memiliki arsip, urutkan berdasarkan nama
        $owners = User::whereHas('archives')->orderBy('name')->get();

        // =================================================================
        // 2. MEMBANGUN QUERY DASAR BERDASARKAN ROLE
        // =================================================================
        $query = Archive::query();

        if (strtolower($user->role->name) !== 'director') {
            $query->where(function ($q) use ($user) {
                $q->where('is_public', true)
                    ->orWhere('user_id', $user->id);
            });
        }

        // =================================================================
        // 3. MENERAPKAN FILTER SECARA DINAMIS
        // =================================================================

        // Filter Pencarian Umum (Nama, Deskripsi, atau Tag)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('tags', function ($tagQ) use ($search) {
                        $tagQ->where('name', 'like', "%{$search}%");
                    });
            });
        });

        // Filter berdasarkan Kategori
        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->where('category', $request->category);
        });

        // Filter berdasarkan Tipe (file/url)
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        // Filter berdasarkan Pemilik (Owner)
        $query->when($request->filled('owner'), function ($q) use ($request) {
            $q->where('user_id', $request->owner);
        });


        $perPage = Auth::user()->settings['per_page'] ?? 15;

        // =================================================================
        // 4. FINALISASI QUERY DAN KIRIM DATA KE VIEW
        // =================================================================
        $archives = $query->with(['user', 'tags'])
            // [UBAH] Tambahkan withCount dan withExists untuk status favorit
            ->withCount('favoritedBy')
            ->withExists(['favoritedBy as is_favorited' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->latest()
            ->paginate($perPage)
            ->appends($request->query()); // <-- SANGAT PENTING untuk paginasi

        return view('archives.index', compact('archives', 'categories', 'owners'));
    }

    /**
     * Menangani aksi suka/tidak suka (toggle).
     */
    public function toggleFavorite(Archive $archive)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Toggle akan attach jika belum ada, dan detach jika sudah ada
        $user->favorites()->toggle($archive->id);

        // Muat ulang jumlah favorit untuk dikirim kembali
        $archive->loadCount('favoritedBy');

        return response()->json([
            'favorited_by_count' => $archive->favorited_by_count,
            'is_favorited' => $user->favorites()->where('archive_id', $archive->id)->exists(),
        ]);
    }

    /**
     * Menampilkan halaman daftar arsip yang difavoritkan.
     */
    public function favorites(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $categories = Config::get('blackfile.archive_categories', []);

        // [PERBAIKAN] Kembali ke query relasi Eloquent yang murni dan sederhana
        $query = $user->favorites(); // Ini akan mengambil semua arsip yang difavoritkan user

        // Terapkan filter jika ada
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('tags', function ($tagQ) use ($search) {
                        $tagQ->where('name', 'like', "%{$search}%");
                    });
            });
        });
        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->where('category', $request->category);
        });
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        $favorites = $query->with(['user', 'tags'])
            ->withCount('favoritedBy')
            // [PERBAIKAN PENTING] Cara yang benar untuk sorting berdasarkan pivot
            ->orderBy('pivot_created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('archives.favorit', [
            'favorites' => $favorites,
            'categories' => $categories
        ]);
    }

    public function show(Archive $archive)
    {
        $this->authorize('view', $archive);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Muat (load) informasi favorit secara manual ke dalam objek $archive
        // sebelum dikirim ke view.
        $archive->loadCount('favoritedBy');
        $archive->is_favorited = $user->favorites()->where('archive_id', $archive->id)->exists();

        return view('archives.show', compact('archive'));
    }


    public function create()
    {
        // Ambil kategori dari file config dan kirim ke view
        $categories = Config::get('blackfile.archive_categories', []);
        return view('archives.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'type' => ['required', Rule::in(['file', 'url'])],
            'category' => ['required', Rule::in(Config::get('blackfile.archive_categories', []))],
            'category_other' => 'nullable|required_if:category,Other|string|max:255',
            'archive_file' => [
                'required_if:type,file',
                File::types(['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx', 'zip', 'rar', 'mp4', 'mp3', 'm4a', 'gif'])
                    ->max('8mb'),
            ],
            // --- [INI PERBAIKANNYA] ---
            'links' => 'required_if:type,url|nullable|string',
            'tags' => 'nullable|string',
        ]);

        $dataToStore = [
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'is_public' => $request->has('is_public'),
            'category' => $validated['category'],
            'category_other' => $validated['category_other'] ?? null,
        ];

        if ($validated['type'] === 'file' && $request->hasFile('archive_file')) {
            $file = $request->file('archive_file');
            $path = $file->store('archives', 'public_uploads');

            $dataToStore['file_path'] = $path;
            $dataToStore['mime_type'] = $file->getClientMimeType();
            $dataToStore['size'] = $file->getSize();
        }

        if ($validated['type'] === 'url') {
            // Cek apakah 'links' tidak null sebelum diproses
            if (!empty($validated['links'])) {
                $linksArray = array_filter(array_map('trim', explode("\n", $validated['links'])));
                $dataToStore['links'] = $linksArray;
            }
        }

        $archive = Archive::create($dataToStore);

        // --- [LOGIKA BARU UNTUK TAGS] ---
        if (!empty($validated['tags'])) {
            $this->syncTags($validated['tags'], $archive);
        }

        // Jika request datang dari AJAX (JavaScript), kirim respons JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Arsip berhasil ditambahkan.',
                'redirect_url' => route('archives.index')
            ], 201); // 201 Created
        }
        // Jika request biasa (tanpa JavaScript), lakukan redirect seperti biasa
        return redirect()->route('archives.index')->with('success', 'Arsip berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit arsip.
     */
    public function edit(Archive $archive)
    {
        $this->authorize('update', $archive);

        // Kirim juga data kategori ke view edit
        $categories = Config::get('blackfile.archive_categories', []);
        return view('archives.edit', compact('archive', 'categories'));
    }

    /**
     * Memperbarui data arsip di database.
     */
    public function update(Request $request, Archive $archive)
    {
        $this->authorize('update', $archive);

        // Validasi tetap berjalan seperti biasa. Aturan 'nullable' sudah benar.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'category' => ['required', Rule::in(Config::get('blackfile.archive_categories', []))],
            'category_other' => 'nullable|required_if:category,Other|string|max:255',
            'links' => 'required_if:type,url|nullable|string',
            'tags' => 'nullable|string',
        ]);

        // 1. Update field yang selalu ada dari data yang divalidasi
        $archive->name = $validated['name'];
        $archive->description = $validated['description'];

        // 2. Update field 'is_public' secara terpisah dengan memeriksa request asli
        $archive->is_public = $request->has('is_public');

        $archive->category = $validated['category'];
        $archive->category_other = $validated['category_other'] ?? null;

        // 3. Update field 'links' jika arsipnya bertipe URL
        if ($archive->type === 'url') {
            if (!empty($validated['links'])) {
                $linksArray = array_filter(array_map('trim', explode("\n", $validated['links'])));
                $archive->links = $linksArray;
            } else {
                $archive->links = []; // Kosongkan jika textarea dihapus isinya
            }
        }

        // 4. Simpan semua perubahan ke database
        $archive->save();

        // --- [LOGIKA BARU UNTUK TAGS] ---
        $this->syncTags($validated['tags'] ?? null, $archive);

        return redirect()->route('archives.index')->with('success', 'Arsip berhasil diperbarui.');
    }

    // --- [TAMBAHKAN METHOD BANTU BARU DI BAWAH INI] ---
    private function syncTags(?string $tagsString, Archive $archive): void
    {
        if (is_null($tagsString)) {
            // Jika string tag kosong/null, hapus semua tag dari arsip
            $archive->tags()->sync([]);
            return;
        }

        // Pecah string menjadi array, bersihkan setiap tag
        $tagNames = array_unique(array_filter(array_map('trim', explode(',', $tagsString))));

        $tagIds = [];
        foreach ($tagNames as $tagName) {
            // Cari tag, atau buat jika tidak ada
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }

        // Sync: Laravel akan secara otomatis attach/detach tag sesuai kebutuhan
        $archive->tags()->sync($tagIds);
    }

    public function destroy(Archive $archive)
    {
        $this->authorize('delete', $archive);

        if ($archive->type === 'file' && $archive->file_path) {
            // --- MENGGUNAKAN KONFIGURASI ANDA ---
            // Hapus file dari disk 'public_uploads'
            Storage::disk('public_uploads')->delete($archive->file_path);
        }

        $archive->delete();

        return redirect()->route('archives.index')->with('success', 'Arsip berhasil dihapus.');
    }
}
