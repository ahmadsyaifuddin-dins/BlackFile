<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;


class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Mulai query kosong
        $query = Archive::query();

        // Cek apakah user BUKAN Director
        if (strtolower($user->role->name) !== 'director') {
            // Agent hanya bisa melihat arsip yang public ATAU miliknya sendiri
            $query->where('is_public', true)
                ->orWhere('user_id', $user->id);
        }

        // Jika user ADALAH Director, tidak ada filter 'where' yang diterapkan,
        // sehingga mereka akan melihat semua arsip.

        // Tambahkan relasi user untuk ditampilkan di view, lalu urutkan dan paginasi
        $archives = $query->with('user')->latest()->paginate(15);

        return view('archives.index', compact('archives'));
    }

    public function show(Archive $archive)
    {
        $this->authorize('view', $archive);

        return view('archives.show', compact('archive'));
    }


    public function create()
    {
        return view('archives.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'nullable|boolean', // <-- Tambahkan validasi
            'type' => ['required', Rule::in(['file', 'url'])],
            'archive_file' => [
                'required_if:type,file',
                File::types(['pdf', 'jpg', 'jpeg', 'png', 'docx', 'xlsx', 'zip', 'rar'])
                    ->max('8mb'),
            ],
            // --- [INI PERBAIKANNYA] ---
            'links' => 'required_if:type,url|nullable|string',
        ]);

        $dataToStore = [
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'is_public' => $request->has('is_public'), // <-- Tambahkan ini
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

        Archive::create($dataToStore);

        return redirect()->route('archives.index')->with('success', 'Arsip berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit arsip.
     */
    public function edit(Archive $archive)
    {
        $this->authorize('update', $archive);

        return view('archives.edit', compact('archive'));
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
            'links' => 'required_if:type,url|nullable|string',
        ]);

        // --- [PERBAIKAN LOGIKA DI SINI] ---

        // 1. Update field yang selalu ada dari data yang divalidasi
        $archive->name = $validated['name'];
        $archive->description = $validated['description'];

        // 2. Update field 'is_public' secara terpisah dengan memeriksa request asli
        $archive->is_public = $request->has('is_public');

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

        return redirect()->route('archives.index')->with('success', 'Arsip berhasil diperbarui.');
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
