<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\EntityImage; // <-- PENTING: Import EntityImage

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entities = Entity::latest()->paginate(9);
        return view('entities.index', compact('entities'));
    }

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
            'status' => 'required|in:ACTIVE,CONTAINED,NEUTRALIZED,UNKNOWN,MYTHOS',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // Validasi untuk setiap file
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',

            'image_url' => 'nullable|url', // Memastikan input adalah URL yang valid
        ]);

         // Buat entitas tanpa data gambar terlebih dahulu
         $entityData = $request->except(['images', 'image_url']);
         $entity = Entity::create($entityData);
 
         // Proses upload file (jika ada)
         if ($request->hasFile('images')) {
             foreach ($request->file('images') as $imageFile) {
                 $path = $imageFile->store('entity_images', 'public_uploads');
                 $entity->images()->create([
                     'path' => $path,
                     'caption' => 'Uploaded Evidence',
                 ]);
             }
         }
         
         // --- LOGIKA BARU: Proses input URL gambar (jika ada) ---
         if ($request->filled('image_url')) {
             $entity->images()->create([
                 'path' => $request->input('image_url'),
                 'caption' => 'Linked Evidence',
             ]);
         }
        return redirect()->route('entities.index')->with('success', 'Entity created successfully.');
    }

    public function show(Entity $entity)
    {
        // Eager load relasi images untuk menghindari N+1 query problem
        $entity->load('images');
        return view('entities.show', compact('entity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entity $entity)
    {
        return view('entities.edit', compact('entity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entity $entity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan codename unik, TAPI abaikan entitas yang sedang diedit
            'codename' => ['nullable', 'string', 'max:255', Rule::unique('entities')->ignore($entity->id)],
            'category' => 'required|string|max:255',
            'rank' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'required|string',
            'abilities' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'status' => 'required|in:ACTIVE,CONTAINED,NEUTRALIZED,UNKNOWN,MYTHOS',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',

            'image_url' => 'nullable|url', // Memastikan input adalah URL yang valid

            // --- VALIDASI BARU ---
            'images_to_delete' => 'nullable|array',
            'images_to_delete.*' => 'exists:entity_images,id', // Pastikan ID gambar yang dikirim ada di database
        ]);

        // --- LOGIKA BARU: HAPUS GAMBAR YANG DIPILIH ---
        if ($request->has('images_to_delete')) {
            $imagesToDelete = EntityImage::whereIn('id', $request->input('images_to_delete'))
                ->where('entity_id', $entity->id) // Keamanan: pastikan gambar milik entitas ini
                ->get();

            foreach ($imagesToDelete as $image) {
                // Hanya hapus file fisik jika itu bukan URL
                if (!filter_var($image->path, FILTER_VALIDATE_URL)) {
                    Storage::disk('public_uploads')->delete($image->path);
                }                
                // 2. Hapus record dari database
                $image->delete();
            }
        }

        // --- LOGIKA LAMA: PROSES GAMBAR BARU ---
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('entity_images', 'public_uploads');
                $entity->images()->create([
                    'path' => $path,
                    'caption' => $request->captions[$index] ?? null,
                ]);
            }
        }

        // --- LOGIKA BARU: Proses input URL gambar ---
        if ($request->filled('image_url')) {
            $entity->images()->create([
                'path' => $request->input('image_url'),
                'caption' => 'Linked Evidence',
            ]);
        }

        // Update data utama entitas (setelah proses gambar agar tidak error jika validasi gagal)
        $entityData = $request->except(['images', 'images_to_delete', 'image_url']);
        $entity->update($entityData);

        return redirect()->route('entities.show', $entity)->with('success', 'Entity record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entity $entity)
    {
        // 1. Hapus file gambar dari storage terlebih dahulu
        foreach ($entity->images as $image) {
            Storage::disk('public_uploads')->delete($image->path);
        }

        // 2. Hapus record dari database
        // (Relasi di migration akan menghapus record di 'entity_images' secara otomatis)
        $entity->delete();

        return redirect()->route('entities.index')->with('success', 'Entity record has been terminated.');
    }
}
