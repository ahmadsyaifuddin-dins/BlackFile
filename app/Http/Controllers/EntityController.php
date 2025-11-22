<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\EntityImage;
use App\Models\SystemSetting;
use App\Models\User;
use App\Services\BlackFileIntelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil opsi filter langsung dari Database (Distinct Values)
        $categories = Entity::whereNotNull('category')->distinct()->orderBy('category')->pluck('category', 'category');
        $ranks = Entity::whereNotNull('rank')->distinct()->orderBy('rank')->pluck('rank', 'rank');
        $origins = Entity::whereNotNull('origin')->distinct()->orderBy('origin')->pluck('origin', 'origin');
        $statuses = Entity::whereNotNull('status')->distinct()->orderBy('status')->pluck('status', 'status');

        $query = Entity::query();

        if ($request->filled('search')) {
            $searchTerm = '%'.$request->search.'%';
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

        // Tambahkan filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->with(['images', 'thumbnail']);

        $perPage = Auth::user()->settings['per_page'] ?? 9;

        $entities = $query->orderBy('id', 'asc')->paginate($perPage);

        // Passing variabel filter ke view
        return view('entities.index', compact('entities', 'categories', 'ranks', 'origins', 'statuses'));
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

        // 1. Buat entitas
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
            if ($thumbnailSelection === 'new_url') {
                $newThumbnailId = $image->id;
            }
        }

        // Proses upload file (Universal)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {

                // Buat nama unik
                $filename = uniqid().'.'.$imageFile->extension();

                // Simpan Fisik menggunakan Disk 'main_uploads'
                // Ini akan masuk ke folder 'uploads/entity_images' secara otomatis
                $imageFile->storeAs('entity_images', $filename, 'main_uploads');

                // Simpan Path Bersih ke Database (entity_images/foto.jpg)
                $dbPath = 'entity_images/'.$filename;

                $image = $entity->images()->create([
                    'path' => $dbPath,
                    'caption' => 'Uploaded Evidence',
                ]);

                if ($thumbnailSelection === 'new_index_'.$index) {
                    $newThumbnailId = $image->id;
                }
            }
        }

        // 4. Update entitas dengan ID thumbnail
        if ($newThumbnailId) {
            $entity->thumbnail_image_id = $newThumbnailId;
            $entity->save();
        }

        try {
            if (SystemSetting::check('entity_notify_enabled', true)) {
                $recipients = User::where('confirmed', true)
                    ->where('id', '!=', Auth::id())
                    ->whereHas('role', function ($query) {
                        $query->where('name', '!=', 'admin');
                    })->get();

                if ($recipients->isNotEmpty()) {
                    Mail::to($recipients)->send(new \App\Mail\NewEntityAlert($entity));
                }
            }
        } catch (\Exception $e) {
            Log::error('SMTP Notification Failed for entity '.$entity->codename.': '.$e->getMessage());
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

        $thumbnailSelection = $request->input('thumbnail_selection');
        $newThumbnailId = null;
        $finalThumbnailId = $entity->thumbnail_image_id;

        // 1. [MODIFIKASI] HAPUS GAMBAR YANG DIPILIH
        if ($request->has('images_to_delete')) {
            $deletedIds = $request->input('images_to_delete');
            $imagesToDelete = EntityImage::whereIn('id', $deletedIds)
                ->where('entity_id', $entity->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                if (! filter_var($image->path, FILTER_VALIDATE_URL)) {
                    // Bersihkan path dari 'uploads/' jika ada (Fix data lama hosting)
                    $cleanPath = Str::replaceFirst('uploads/', '', $image->path);

                    // Hapus file fisik via disk
                    if (Storage::disk('main_uploads')->exists($cleanPath)) {
                        Storage::disk('main_uploads')->delete($cleanPath);
                    }
                }
                $image->delete();
            }

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
            if ($thumbnailSelection === 'new_url') {
                $newThumbnailId = $image->id;
            }
        }

        // 3. PROSES UPLOAD FILE BARU (Universal)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {

                $filename = uniqid().'.'.$imageFile->extension();

                // Simpan ke Disk main_uploads
                $imageFile->storeAs('entity_images', $filename, 'main_uploads');
                $dbPath = 'entity_images/'.$filename;

                $image = $entity->images()->create([
                    'path' => $dbPath,
                    'caption' => 'Uploaded Evidence',
                ]);

                if ($thumbnailSelection === 'new_index_'.$index) {
                    $newThumbnailId = $image->id;
                }
            }
        }

        // 4. TENTUKAN THUMBNAIL_ID FINAL
        if ($newThumbnailId) {
            $finalThumbnailId = $newThumbnailId;
        } elseif (is_numeric($thumbnailSelection)) {
            if ($entity->images()->where('id', $thumbnailSelection)->exists()) {
                $finalThumbnailId = $thumbnailSelection;
            }
        }

        // 5. UPDATE DATA UTAMA ENTITAS
        $entityData = $request->except(['images', 'images_to_delete', 'image_url', 'thumbnail_selection']);
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
            if (! filter_var($image->path, FILTER_VALIDATE_URL)) {
                $cleanPath = Str::replaceFirst('uploads/', '', $image->path);

                if (Storage::disk('main_uploads')->exists($cleanPath)) {
                    Storage::disk('main_uploads')->delete($cleanPath);
                }
            }
        }
        $entity->delete();

        return redirect()->route('entities.index')->with('success', 'Entity record has been terminated.');
    }

    /**
     * Menampilkan Halaman Tactical Assessment Console
     */
    public function assessment(Entity $entity)
    {
        // Pastikan stats tidak null saat dikirim ke view (untuk menghindari error JS)
        if (! $entity->combat_stats) {
            $entity->combat_stats = [
                'strength' => 0, 'speed' => 0, 'durability' => 0,
                'intelligence' => 0, 'energy' => 0, 'combat_skill' => 0,
            ];
        }

        return view('entities.assessment', compact('entity'));
    }

    /**
     * Menyimpan Data Stats Baru
     */
    public function updateAssessment(Request $request, Entity $entity)
    {
        $validated = $request->validate([
            'power_tier' => 'required|integer|min:1|max:10',
            'combat_type' => 'required|in:AGGRESSOR,HAZARD',
            'stats.strength' => 'required|integer|min:0|max:100',
            'stats.speed' => 'required|integer|min:0|max:100',
            'stats.durability' => 'required|integer|min:0|max:100',
            'stats.intelligence' => 'required|integer|min:0|max:100',
            'stats.energy' => 'required|integer|min:0|max:100',
            'stats.combat_skill' => 'required|integer|min:0|max:100',
        ]);

        $entity->update([
            'power_tier' => $validated['power_tier'],
            'combat_type' => $validated['combat_type'],
            'combat_stats' => $validated['stats'], // Laravel otomatis encode ke JSON karena casting di Model
        ]);

        return redirect()->route('entities.show', $entity)
            ->with('success', 'Tactical Profile updated successfully.');
    }

    /**
     * Generate AI Assessment via AJAX
     */
    public function generateAiAssessment(Entity $entity, BlackFileIntelService $aiService)
    {
        // 1. Panggil Service AI
        $result = $aiService->generateAssessment(
            $entity->name,
            $entity->description,
            $entity->abilities ?? 'Unknown',
            $entity->origin ?? 'Unknown',
            $entity->category
        );

        // 2. Jika gagal, return error JSON
        if (! $result) {
            return response()->json(['error' => 'Failed to communicate with Tactical AI HQ.'], 500);
        }

        // 3. Mapping agar sesuai dengan format Alpine JS
        // BlackFile Rulebook combat types ke mapping sederhana form kamu (AGGRESSOR / HAZARD)
        $simplifiedType = 'AGGRESSOR';
        if (in_array(strtoupper($result['combat_type']), ['HAZARD', 'MYSTIC', 'COSMIC'])) {
            // Opsional: Jika kamu mau mapping tipe AI yang kompleks ke 2 tipe form kamu
            // Jika form kamu cuma punya AGGRESSOR dan HAZARD, kita bisa paksa logic sederhana
            $simplifiedType = ($result['combat_type'] == 'HAZARD') ? 'HAZARD' : 'AGGRESSOR';
        }

        return response()->json([
            'power_tier' => $result['power_tier'],
            'combat_type' => $simplifiedType, // Atau gunakan $result['combat_type'] jika opsi select ditambah
            'combat_stats' => $result['combat_stats'],
            'reasoning' => $result['reasoning'],
        ]);
    }
}
