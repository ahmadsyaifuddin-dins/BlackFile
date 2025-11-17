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
        @method('PUT')
    @endif

    {{-- BAGIAN 1: INFORMASI DASAR --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Name --}}
        <div>
            <label for="name" class="block text-primary mb-1">> NAME:</label>
            <x-forms.input 
                name="name" 
                id="name"
                required 
                :value="old('name', $entity->name ?? null)"
                placeholder="Input entity name..." 
            />
        </div>

        {{-- Codename --}}
        <div>
            <label for="codename" class="block text-primary mb-1">> CODENAME:</label>
            <x-forms.input 
                name="codename" 
                id="codename"
                :value="old('codename', $entity->codename ?? null)"
                placeholder="e.g., SCP-173, Subject-Alpha..." 
            />
        </div>
    </div>

    {{-- BAGIAN 2: KATEGORISASI (DROPDOWNS) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Category (Searchable) --}}
        <x-forms.select 
            label="> CATEGORY:"
            name="category"
            :options="config('blackfile.entity_categories')"
            :selected="old('category', $entity->category ?? '')"
            placeholder="-- Select Category --"
            :searchable="true" 
        />

        {{-- Rank (Searchable) --}}
        <x-forms.select 
            label="> RANK:"
            name="rank"
            :options="config('blackfile.entity_ranks')"
            :selected="old('rank', $entity->rank ?? '')"
            placeholder="-- Select Rank --"
            :searchable="true" 
        />

        {{-- Origin (Searchable) --}}
        <x-forms.select 
            label="> ORIGIN:"
            name="origin"
            :options="config('blackfile.entity_origins')"
            :selected="old('origin', $entity->origin ?? '')"
            placeholder="-- Select Origin --"
            :searchable="true" 
        />

        {{-- Status (Manual Array) --}}
        {{-- Kita definisikan opsi status langsung di sini --}}
        @php
            $statusOptions = [
                'UNKNOWN' => 'UNKNOWN',
                'ACTIVE' => 'ACTIVE',
                'CONTAINED' => 'CONTAINED',
                'NEUTRALIZED' => 'NEUTRALIZED',
            ];
        @endphp
        <x-forms.select 
            label="> STATUS:"
            name="status"
            :options="$statusOptions"
            :selected="old('status', $entity->status ?? 'UNKNOWN')"
        />
        
    </div>

    {{-- BAGIAN 3: DETAIL TEXTAREAS (Custom Component Kamu) --}}
    <x-entities.form-textareas 
        :description="old('description', $entity->description ?? null)"
        :abilities="old('abilities', $entity->abilities ?? null)"
        :weaknesses="old('weaknesses', $entity->weaknesses ?? null)"
    />

    {{-- BAGIAN 4: MEDIA UPLOAD (Custom Component Kamu) --}}
    <x-entities.form-image-upload :entity="$entity" />

    {{-- FOOTER: TOMBOL AKSI --}}
    <div class="flex justify-end">
        <x-button type="submit">
            > {{ $entity ? 'SAVE CHANGES' : 'EXECUTE REGISTRATION' }}
        </x-button>
    </div>
</form>