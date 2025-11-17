{{-- File: resources/views/entities/_form.blade.php --}}
@props([
    'entity' => null, // Akan null saat 'create' dan berisi data saat 'edit'
])

<form 
    action="{{ $entity ? route('entities.update', $entity) : route('entities.store') }}" 
    method="POST" 
    enctype="multipart/form-data"
    class="space-y-8 font-mono"
>
    @csrf
    @if($entity)
        @method('PUT') {{-- Hanya ada saat edit --}}
    @endif

    {{-- Baris 1: Name & Codename --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="flex items-center gap-4">
            <label for="name" class="flex-shrink-0 text-primary">> NAME:</label>
            <input type="text" name="name" id="name" required 
                   value="{{ old('name', $entity->name ?? null) }}"
                   placeholder="Input entity name..."
                   class="w-full bg-transparent border-0 border-b-2 border-border-color focus:border-primary focus:ring-0 text-white">
        </div>
        <div class="flex items-center gap-4">
            <label for="codename" class="flex-shrink-0 text-primary">> CODENAME:</label>
            <input type="text" name="codename" id="codename" 
                   value="{{ old('codename', $entity->codename ?? null) }}"
                   placeholder="e.g., SCP-173, Subject-Alpha..."
                   class="w-full bg-transparent border-0 border-b-2 border-border-color focus:border-primary focus:ring-0 text-white">
        </div>
    </div>

    {{-- Baris 2: Dropdowns --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <x-forms.searchable-dropdown 
            label="> CATEGORY:"
            name="category"
            :options="config('blackfile.entity_categories')"
            :selected="old('category', $entity->category ?? config('blackfile.entity_categories')[0] ?? '')"
        />

        <x-forms.searchable-dropdown 
            label="> RANK:"
            name="rank"
            :options="config('blackfile.entity_ranks')"
            :selected="old('rank', $entity->rank ?? config('blackfile.entity_ranks')[0] ?? '')"
        />

        <x-forms.searchable-dropdown 
            label="> ORIGIN:"
            name="origin"
            :options="config('blackfile.entity_origins')"
            :selected="old('origin', $entity->origin ?? config('blackfile.entity_origins')[0] ?? '')"
        />

        <x-forms.status-dropdown 
            :selected="old('status', $entity->status ?? 'UNKNOWN')" 
        />
        
    </div>

    {{-- Textareas --}}
    <x-entities.form-textareas 
        :description="old('description', $entity->description ?? null)"
        :abilities="old('abilities', $entity->abilities ?? null)"
        :weaknesses="old('weaknesses', $entity->weaknesses ?? null)"
    />

    {{-- Image Upload --}}
    <x-entities.form-image-upload :entity="$entity" /> {{-- $entity akan null (create) or berisi data (edit) --}}

    {{-- Tombol Aksi --}}
    <div class="flex justify-end pt-4">
        <x-button type="submit">
            > {{ $entity ? 'SAVE CHANGES' : 'EXECUTE' }}
        </x-button>
    </div>
</form>