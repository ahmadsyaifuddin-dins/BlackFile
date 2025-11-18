<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\EntityImage;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Mail\NewEntityAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Entity::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('codename', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('rank')) {
            $query->where('rank', $request->rank);
        }

        if ($request->filled('origin')) {
            $query->where('origin', $request->origin);
        }

        // [LOGIKA BARU] Eager load relasi 'thumbnail' dan 'images'
        // Ini akan mengoptimalkan query di halaman index
        $query->with(['images', 'thumbnail']);

        $perPage = Auth::user()->settings['per_page'] ?? 9;

        $entities = $query->orderBy('id', 'asc')->paginate($perPage);

        return view('entities.index', compact('entities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => 'nullable|string|max:255|unique:entities,codename',
            'category' => 'required|string|max:255',
            'rank' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'required|string',
            'abilities' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image_url' => 'nullable|url',
            'thumbnail_selection' => 'nullable|string', 
        ]);

        // 1. Buat entitas dulu (tanpa thumbnail_image_id)
        $entityData = $request->except(['images', 'image_url', 'thumbnail_selection']);
        $entity = Entity::create($entityData);

        $newThumbnailId = null;
        $thumbnailSelection = $request->input('thumbnail_selection');

        // 2. Proses URL (jika ada)
        if ($request->filled('image_url')) {
            $image = $entity->images()->create([
                'path' => $request->input('image_url'),
                'caption' => 'Linked Evidence',
            ]);
            // Jika 'new_url' dipilih sebagai thumbnail
            if ($thumbnailSelection === 'new_url') {
                $newThumbnailId = $image->id;
            }
        }

        // 3. Proses upload file (jika ada)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('entity_images', 'public_uploads');
                $image = $entity->images()->create([
                    'path' => $path,
                    'caption' => 'Uploaded Evidence',
                ]);

                // Jika 'new_index_X' dipilih sebagai thumbnail
                if ($thumbnailSelection === 'new_index_' . $index) {
                    $newThumbnailId = $image->id;
                }
            }
        }

        // 4. [LOGIKA BARU] Update entitas dengan ID thumbnail
        if ($newThumbnailId) {
            $entity->thumbnail_image_id = $newThumbnailId;
            $entity->save();
        }

        try {
            $recipients = User::where('id', '!=', Auth::id())->get();
            if ($recipients->isNotEmpty()) {
                Mail::to($recipients)->send(new NewEntityAlert($entity));
            }
        } catch (\Exception $e) {
            Log::error('SMTP Notification Failed for new entity ' . $entity->id . ': ' . $e->getMessage());
            return redirect()->route('entities.index')
                ->with('warning', 'Entity created, but failed to send email alerts. Check log.');
        }

        return redirect()->route('entities.index')->with('success', 'Entity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Entity $entity)
    {
        $entity->load('images');
        return view('entities.show', compact('entity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entity $entity)
    {
        // Eager load images agar bisa ditampilkan di form
        $entity->load('images'); 
        return view('entities.edit', compact('entity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entity $entity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'codename' => ['nullable', 'string', 'max:255', Rule::unique('entities')->ignore($entity->id)],
            'category' => 'required|string|max:255',
            'rank' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'required|string',
            'abilities' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'status' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image_url' => 'nullable|url',
            'images_to_delete' => 'nullable|array',
            'images_to_delete.*' => 'exists:entity_images,id',
            'thumbnail_selection' => 'nullable|string',
        ]);

        // [LOGIKA BARU]
        $thumbnailSelection = $request->input('thumbnail_selection');
        $newThumbnailId = null;
        $finalThumbnailId = $entity->thumbnail_image_id; // Mulai dengan ID yang ada

        // 1. HAPUS GAMBAR YANG DIPILIH
        if ($request->has('images_to_delete')) {
            $deletedIds = $request->input('images_to_delete');
            $imagesToDelete = EntityImage::whereIn('id', $deletedIds)
                ->where('entity_id', $entity->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                if (!filter_var($image->path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public_uploads')->delete($image->path);
                }
                $image->delete();
            }

            // [BARU] Jika thumbnail yang sekarang ikut terhapus, set jadi null
            if (in_array($finalThumbnailId, $deletedIds)) {
                $finalThumbnailId = null;
            }
        }

        // 2. PROSES GAMBAR BARU (URL)
        if ($request->filled('image_url')) {
            $image = $entity->images()->create([
                'path' => $request->input('image_url'),
                'caption' => 'Linked Evidence',
            ]);
            // Jika 'new_url' dipilih, catat ID-nya
            if ($thumbnailSelection === 'new_url') {
                $newThumbnailId = $image->id;
            }
        }

        // 3. PROSES GAMBAR BARU (FILE UPLOAD)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('entity_images', 'public_uploads');
                $image = $entity->images()->create([
                    'path' => $path,
                    'caption' => 'Uploaded Evidence',
                ]);
                
                // Jika 'new_index_X' dipilih, catat ID-nya
                if ($thumbnailSelection === 'new_index_' . $index) {
                    $newThumbnailId = $image->id;
                }
            }
        }

        // 4. TENTUKAN THUMBNAIL_ID FINAL
        if ($newThumbnailId) {
            // Prioritas 1: Gambar baru (upload/url)
            $finalThumbnailId = $newThumbnailId;
        } elseif (is_numeric($thumbnailSelection)) {
            // Prioritas 2: Gambar lama yang ada
            // Pastikan ID itu masih ada (tidak dihapus) dan milik entitas ini
            if ($entity->images()->where('id', $thumbnailSelection)->exists()) {
                $finalThumbnailId = $thumbnailSelection;
            }
        }
        // Jika tidak ada di atas, $finalThumbnailId akan tetap ID lama (atau null jika dihapus)

        // 5. UPDATE DATA UTAMA ENTITAS
        $entityData = $request->except(['images', 'images_to_delete', 'image_url', 'thumbnail_selection']);
        // [BARU] Sertakan ID thumbnail final
        $entityData['thumbnail_image_id'] = $finalThumbnailId;
        
        $entity->update($entityData);

        return redirect()->route('entities.show', $entity)->with('success', 'Entity record updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        foreach ($entity->images as $image) {
            if (!filter_var($image->path, FILTER_VALIDATE_URL)) {
                Storage::disk('public_uploads')->delete($image->path);
            }
        }
        $entity->delete();

        return redirect()->route('entities.index')->with('success', 'Entity record has been terminated.');
    }
}