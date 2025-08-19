<x-app-layout :title="'Edit: ' . ($entity->codename ?? $entity->name)">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-2xl font-bold text-primary">Edit Record // {{ $entity->codename ?? $entity->name }}</h2>
        
        {{-- Tombol Kembali ke halaman Show --}}
        <a href="{{ route('entities.show', $entity) }}" class="w-full sm:w-auto text-center bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 rounded hover:text-primary hover:border-primary transition-colors">
            &lt; Cancel and View
        </a>
    </div>
    <div class="bg-surface border border-border-color rounded-lg p-6">
        {{-- Arahkan form ke route 'update' dan gunakan method 'PUT' --}}
        <form action="{{ route('entities.update', $entity) }}" method="POST" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Name & Codename --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-secondary">> NAME</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $entity->name) }}"
                        class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="codename" class="block text-sm font-medium text-secondary">> CODENAME</label>
                    <input type="text" name="codename" id="codename" value="{{ old('codename', $entity->codename) }}"
                        class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            {{-- Category, Rank, Origin, Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-secondary">> CATEGORY</label>
                    <input type="text" name="category" id="category" required
                        value="{{ old('category', $entity->category) }}"
                        class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="rank" class="block text-sm font-medium text-secondary">> RANK</label>
                    <input type="text" name="rank" id="rank" value="{{ old('rank', $entity->rank) }}"
                        class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="origin" class="block text-sm font-medium text-secondary">> ORIGIN</label>
                    <input type="text" name="origin" id="origin" value="{{ old('origin', $entity->origin) }}"
                        class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-secondary">> STATUS</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="UNKNOWN" @selected(old('status', $entity->status) == 'UNKNOWN')>UNKNOWN</option>
                        <option value="ACTIVE" @selected(old('status', $entity->status) == 'ACTIVE')>ACTIVE</option>
                        <option value="CONTAINED" @selected(old('status', $entity->status) == 'CONTAINED')>CONTAINED
                        </option>
                        <option value="NEUTRALIZED" @selected(old('status', $entity->status) ==
                            'NEUTRALIZED')>NEUTRALIZED</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="description" class="block text-sm font-medium text-secondary">> DESCRIPTION</label>
                <textarea name="description" id="description" rows="5" required
                    class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('description', $entity->description) }}</textarea>
            </div>
            <div>
                <label for="abilities" class="block text-sm font-medium text-secondary">> ABILITIES</label>
                <textarea name="abilities" id="abilities" rows="3"
                    class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('abilities', $entity->abilities) }}</textarea>
            </div>
            <div>
                <label for="weaknesses" class="block text-sm font-medium text-secondary">> WEAKNESSES</label>
                <textarea name="weaknesses" id="weaknesses" rows="3"
                    class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('weaknesses', $entity->weaknesses) }}</textarea>
            </div>

            {{-- Existing Images --}}
            @if($entity->images->isNotEmpty())
            <div class="pt-4 border-t border-border-color">
                <label class="block text-sm font-medium text-secondary">> CURRENT IMAGES</label>
                <p class="text-xs text-secondary mb-3">Check the box below an image to mark it for termination upon
                    saving changes.</p>
                <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    @foreach($entity->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('uploads/' . $image->path) }}" alt="{{ $image->caption ?? '' }}"
                            class="w-full h-32 object-cover rounded border-2 border-border-color group-hover:border-primary/50 transition-all">

                        {{-- Checkbox untuk Hapus Gambar --}}
                        <div class="mt-2 text-center">
                            <label for="delete_image_{{ $image->id }}"
                                class="flex items-center justify-center text-xs text-red-400 cursor-pointer">
                                <input type="checkbox" name="images_to_delete[]" value="{{ $image->id }}"
                                    id="delete_image_{{ $image->id }}"
                                    class="w-4 h-4 bg-base border-border-color text-red-600 focus:ring-red-500 mr-2">
                                Mark for Deletion
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Image Upload --}}
            <div>
                <label class="block text-sm font-medium text-secondary mt-4">> UPLOAD NEW IMAGES (To add or
                    replace)</label>
                <input type="file" name="images[]" multiple
                    class="mt-1 block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-black hover:file:bg-primary-hover">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-primary text-black font-bold py-2 px-6 rounded hover:bg-primary-hover transition-colors">
                    > Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>