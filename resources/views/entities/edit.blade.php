<x-app-layout :title="'Edit: ' . ($entity->codename ?? $entity->name)">
    <h2 class="text-2xl font-bold text-primary mb-6">Edit Entity Record // {{ $entity->codename ?? $entity->name }}</h2>

    <div class="bg-surface border border-border-color rounded-lg p-6">
        {{-- Arahkan form ke route 'update' dan gunakan method 'PUT' --}}
        <form action="{{ route('entities.update', $entity) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            {{-- Name & Codename --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-secondary">> NAME</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $entity->name) }}" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="codename" class="block text-sm font-medium text-secondary">> CODENAME</label>
                    <input type="text" name="codename" id="codename" value="{{ old('codename', $entity->codename) }}" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            {{-- Category, Rank, Origin, Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-secondary">> CATEGORY</label>
                    <input type="text" name="category" id="category" required value="{{ old('category', $entity->category) }}" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                 <div>
                    <label for="rank" class="block text-sm font-medium text-secondary">> RANK</label>
                    <input type="text" name="rank" id="rank" value="{{ old('rank', $entity->rank) }}" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="origin" class="block text-sm font-medium text-secondary">> ORIGIN</label>
                    <input type="text" name="origin" id="origin" value="{{ old('origin', $entity->origin) }}" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-secondary">> STATUS</label>
                    <select name="status" id="status" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="UNKNOWN" @selected(old('status', $entity->status) == 'UNKNOWN')>UNKNOWN</option>
                        <option value="ACTIVE" @selected(old('status', $entity->status) == 'ACTIVE')>ACTIVE</option>
                        <option value="CONTAINED" @selected(old('status', $entity->status) == 'CONTAINED')>CONTAINED</option>
                        <option value="NEUTRALIZED" @selected(old('status', $entity->status) == 'NEUTRALIZED')>NEUTRALIZED</option>
                    </select>
                </div>
            </div>

            {{-- Descriptions --}}
            <div>
                <label for="description" class="block text-sm font-medium text-secondary">> DESCRIPTION</label>
                <textarea name="description" id="description" rows="5" required class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('description', $entity->description) }}</textarea>
            </div>
            <div>
                <label for="abilities" class="block text-sm font-medium text-secondary">> ABILITIES</label>
                <textarea name="abilities" id="abilities" rows="3" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('abilities', $entity->abilities) }}</textarea>
            </div>
            <div>
                <label for="weaknesses" class="block text-sm font-medium text-secondary">> WEAKNESSES</label>
                <textarea name="weaknesses" id="weaknesses" rows="3" class="mt-1 block w-full bg-base border-border-color rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('weaknesses', $entity->weaknesses) }}</textarea>
            </div>
            
            {{-- Existing Images --}}
             @if($entity->images->isNotEmpty())
            <div class="pt-4 border-t border-border-color">
                 <label class="block text-sm font-medium text-secondary">> CURRENT IMAGES</label>
                 <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                    @foreach($entity->images as $image)
                        <div class="relative">
                           <img src="{{ asset('uploads/' . $image->path) }}" alt="{{ $image->caption ?? '' }}" class="w-full h-24 object-cover rounded">
                           {{-- (Optional) Tambahkan tombol hapus per-gambar di sini nanti --}}
                        </div>
                    @endforeach
                 </div>
            </div>
            @endif


            {{-- Image Upload --}}
            <div>
                 <label class="block text-sm font-medium text-secondary">> UPLOAD NEW IMAGES</label>
                 <input type="file" name="images[]" multiple class="mt-1 block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-black hover:file:bg-primary-hover">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary text-black font-bold py-2 px-6 rounded hover:bg-primary-hover transition-colors">
                    > Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>