<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entities = Entity::latest()->paginate(10);
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
            'status' => 'required|in:ACTIVE,CONTAINED,NEUTRALIZED,UNKNOWN',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk setiap file
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        $entity = Entity::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                // --- PERUBAHAN DI SINI ---
                // Ganti dari: $path = $imageFile->store('entity_images', 'public');
                // Menjadi:
                $path = $imageFile->store('entity_images', 'public_uploads');
                // --- AKHIR PERUBAHAN ---

                $entity->images()->create([
                    'path' => $path,
                    'caption' => $request->captions[$index] ?? null,
                ]);
            }
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
            'status' => 'required|in:ACTIVE,CONTAINED,NEUTRALIZED,UNKNOWN',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        // Update data utama entitas
        $entity->update($validated);

        // Proses jika ada gambar baru yang diunggah
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('entity_images', 'public_uploads');
                $entity->images()->create([
                    'path' => $path,
                    'caption' => $request->captions[$index] ?? null,
                ]);
            }
        }

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
