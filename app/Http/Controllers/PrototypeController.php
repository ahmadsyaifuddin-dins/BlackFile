<?php

namespace App\Http\Controllers;

use App\Models\Prototype;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Pastikan ini ada
use Illuminate\Support\Str;

class PrototypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil tipe proyek unik untuk opsi filter dropdown
        $projectTypes = Prototype::select('project_type')->distinct()->orderBy('project_type')->pluck('project_type');
        $users = User::select('id', 'name')->orderBy('name')->get();
        // Mulai query builder
        $query = Prototype::query();

        // Terapkan filter PENCARIAN jika ada input 'search'
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('codename', 'like', "%{$searchTerm}%");
            });
        }

        // --- FILTER BARU: PAGINATION ---
        $perPage = Auth::user()->settings['per_page'] ?? 9;

        // Terapkan filter STATUS jika ada input 'status'
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Terapkan filter OWNER jika ada input 'user_id'
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Terapkan filter TIPE PROYEK jika ada input 'project_type'
        if ($request->filled('project_type')) {
            $query->where('project_type', $request->input('project_type'));
        }

        // Lanjutkan query dengan eager loading, urutan, dan paginasi
        $prototypes = $query->with('user')
            ->orderBy('created_at', 'desc') // [PERBAIKAN] Urutkan berdasarkan tanggal (terbaru dulu)
            ->orderBy('id', 'desc') // [PERBAIKAN] Jika ada tanggal yang sama, urutkan berdasarkan ID (terbaru dulu)
            ->paginate($perPage);

        // Kirim data ke view
        return view('prototypes.index', [
            'prototypes' => $prototypes,
            'projectTypes' => $projectTypes,
            'users' => $users
        ]);
    }

    public function create()
    {
        // Kosongkan atau redirect jika diakses langsung
        return redirect()->route('prototypes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => 'required|string|max:50|unique:prototypes,codename',
            'project_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'nullable|string', // Validasi sebagai string, akan di-handle di bawah
            'repository_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'cover_image_url' => 'nullable|url', // [BARU] Validasi untuk URL
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp,gif|max:1024',
            'icon_url' => 'nullable|url', // [BARU] Validasi untuk URL ikon
            'start_date' => 'nullable|date_format:Y-m-d\TH:i',
            'completed_date' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_date',
        ]);

        // [LOGIKA BARU] Handle dua opsi gambar
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/prototypes'), $imageName);
            $validatedData['cover_image_path'] = 'uploads/prototypes/' . $imageName;
        } elseif ($request->filled('cover_image_url')) {
            $validatedData['cover_image_path'] = $request->input('cover_image_url');
        }

        // [BARU] Logika untuk handle dua opsi ikon
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconName = 'icon_' . time() . '.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('uploads/prototypes/icons'), $iconName);
            $validatedData['icon_path'] = 'uploads/prototypes/icons/' . $iconName;
        } elseif ($request->filled('icon_url')) {
            $validatedData['icon_path'] = $request->input('icon_url');
        }

        // Unset semua input sementara
        unset($validatedData['cover_image'], $validatedData['cover_image_url'], $validatedData['icon'], $validatedData['icon_url']);

        if (!empty($validatedData['tech_stack'])) {
            $validatedData['tech_stack'] = array_map('trim', explode(',', $validatedData['tech_stack']));
        }

        $validatedData['user_id'] = Auth::id();
        Prototype::create($validatedData);

        return redirect()->route('prototypes.index')->with('success', 'Prototype project successfully filed.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prototype $prototype)
    {
        // Cukup kirim data prototype yang ditemukan ke view baru
        return view('prototypes.show', compact('prototype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prototype $prototype)
    {
        // Otorisasi (opsional, tapi sangat direkomendasikan)
        // $this->authorize('update', $prototype);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan codename unik, KECUALI untuk dirinya sendiri
            'codename' => 'required|string|max:50|unique:prototypes,codename,' . $prototype->id,
            'project_type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'nullable|string',
            'repository_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // [BARU] Validasi gambar
            'cover_image_url' => 'nullable|url', // [BARU]
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp,gif|max:1024',
            'icon_url' => 'nullable|url', // [BARU] Validasi untuk URL ikon
            'start_date' => 'nullable|date_format:Y-m-d\TH:i',
            'completed_date' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_date',
        ]);

        // [LOGIKA BARU] Handle update dengan dua opsi gambar
        if ($request->hasFile('cover_image')) {
            // Hapus gambar lokal lama jika ada
            if ($prototype->cover_image_path && !Str::startsWith($prototype->cover_image_path, 'http') && File::exists(public_path($prototype->cover_image_path))) {
                File::delete(public_path($prototype->cover_image_path));
            }
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/prototypes'), $imageName);
            $validatedData['cover_image_path'] = 'uploads/prototypes/' . $imageName;
        } elseif ($request->filled('cover_image_url')) {
            // Hapus gambar lokal lama jika user beralih menggunakan URL
            if ($prototype->cover_image_path && !Str::startsWith($prototype->cover_image_path, 'http') && File::exists(public_path($prototype->cover_image_path))) {
                File::delete(public_path($prototype->cover_image_path));
            }
            $validatedData['cover_image_path'] = $request->input('cover_image_url');
        }

        // [BARU] Logika untuk handle update ikon
        if ($request->hasFile('icon')) {
            if ($prototype->icon_path && !Str::startsWith($prototype->icon_path, 'http') && File::exists(public_path($prototype->icon_path))) {
                File::delete(public_path($prototype->icon_path));
            }
            $icon = $request->file('icon');
            $iconName = 'icon_' . time() . '.' . $icon->getClientOriginalExtension();
            $icon->move(public_path('uploads/prototypes/icons'), $iconName);
            $validatedData['icon_path'] = 'uploads/prototypes/icons/' . $iconName;
        } elseif ($request->filled('icon_url')) {
            if ($prototype->icon_path && !Str::startsWith($prototype->icon_path, 'http') && File::exists(public_path($prototype->icon_path))) {
                File::delete(public_path($prototype->icon_path));
            }
            $validatedData['icon_path'] = $request->input('icon_url');
        }

        unset($validatedData['cover_image'], $validatedData['cover_image_url'], $validatedData['icon'], $validatedData['icon_url']);

        if (!empty($validatedData['tech_stack'])) {
            $validatedData['tech_stack'] = array_map('trim', explode(',', $validatedData['tech_stack']));
        } else {
            $validatedData['tech_stack'] = [];
        }

        $prototype->update($validatedData);

        return redirect()->route('prototypes.index')->with('success', 'Prototype project successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prototype $prototype)
    {
        // Otorisasi (opsional tapi penting, pastikan hanya pemilik yang bisa hapus)
        if (auth()->id() !== $prototype->user_id) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        // Hapus gambar dari folder public jika ada
        if ($prototype->cover_image_path && File::exists(public_path($prototype->cover_image_path))) {
            File::delete(public_path($prototype->cover_image_path));
        }

        // Hapus ikon dari folder public jika ada
        if ($prototype->icon_path && File::exists(public_path($prototype->icon_path))) {
            File::delete(public_path($prototype->icon_path));
        }

        // Hapus record dari database
        $prototype->delete();

        return redirect()->route('prototypes.index')->with('success', 'Prototype project has been permanently deleted.');
    }
}
