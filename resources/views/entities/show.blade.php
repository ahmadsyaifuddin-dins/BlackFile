@php
    // origin adalah halaman index (atau halaman sebelumnya) yang mungkin dikirimkan ke show
    $origin = request('return_url') ?? null;

    // Back link dari show: kalau ada origin gunakan origin, kalau nggak balik ke index
    $backToIndex = $origin ?? route('entities.index');

    // Untuk tombol Edit: set return_url ke URL show (agar edit->Cancel balik ke show)
    // dan teruskan origin (jika ada) supaya controller tahu asal-index saat submit
    $editUrl = route('entities.edit', $entity) . '?return_url=' . urlencode(route('entities.show', $entity));
    if ($origin) {
        $editUrl .= '&origin=' . urlencode($origin);
    }
@endphp
<x-app-layout :title="$entity->codename ?? $entity->name">
    {{-- Header dengan style "Classified" --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="min-w-0">
                <p class="text-sm text-secondary font-mono">CLASSIFICATION LEVEL: {{ $entity->rank ?? 'UNKNOWN' }}</p>
                <h1 class="text-xl md:text-3xl font-bold text-primary tracking-widest font-mono text-glow break-words">
                    ENTITY: {{ $entity->codename ?? $entity->name }}
                </h1>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto shrink-0">
    
                {{-- 1. TOMBOL BACK (Outline Green) --}}
                <x-button variant="outline" href="{{ $backToIndex }}" class="w-full md:w-auto justify-center">
                    &lt; BACK
                </x-button>
            
                {{-- 2. TOMBOL EDIT (Outline Green) --}}
                <x-button variant="outline" href="{{ $editUrl }}" class="w-full md:w-auto justify-center">
                    > EDIT FILE
                </x-button>
                
                {{-- 3. TOMBOL TERMINATE (Custom Red Style) --}}
                <x-button.delete 
                    :action="route('entities.destroy', $entity)" 
                    title="TERMINATE ENTITY?"
                    message="WARNING: Are you sure you want to terminate this entity record? This action cannot be undone."
                    target="{{ $entity->name }}"
                    {{-- Styling Khusus agar sesuai gambar (Red Background & Border) --}}
                    class="w-full md:w-auto justify-center px-4 py-2 bg-red-900/50 border border-red-500/50 text-red-400 hover:bg-red-900/80 hover:text-white hover:border-red-500 font-bold rounded-md transition-colors"
                >
                    > TERMINATE
                </x-button.delete>
            
            </div>
        </div>
    </div>
    
    {{-- Layout Utama: Dua Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kolom Kiri: Data Teks --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface border-x-4 border-primary p-4">
                <h3 class="font-bold text-primary font-mono text-lg mb-2">> DATA_STREAM::DESCRIPTION</h3>
                <p class="text-secondary whitespace-pre-wrap leading-relaxed">{{ $entity->description }}</p>
            </div>
            
            {{-- 
                - Mengubah 'grid-cols-2' menjadi 'grid-cols-1 sm:grid-cols-2'.
                - Ini akan membuat kolom-kolom ini bertumpuk di layar kecil.
            --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-mono">
                <div class="bg-surface border-l-2 border-border-color p-3">
                    <p class="text-xs text-secondary">SUBJECT_CATEGORY</p>
                    <p class="font-bold">{{ $entity->category }}</p>
                </div>
                 <div class="bg-surface border-l-2 border-border-color p-3">
                    <p class="text-xs text-secondary">POINT_OF_ORIGIN</p>
                    {{-- Menambahkan 'break-words' untuk memecah teks panjang --}}
                    <p class="font-bold break-words">{{ $entity->origin ?? 'Unknown' }}</p>
                </div>
            </div>

            <div class="bg-surface border border-dashed border-border-color p-4">
                <h3 class="font-bold text-primary font-mono text-lg mb-2">> DATA_STREAM::ABILITIES</h3>
                <p class="text-secondary whitespace-pre-wrap">{{ $entity->abilities ?? 'None Documented.' }}</p>
            </div>
            <div class="bg-red-900/30 border border-red-500/50 p-4">
                <h3 class="font-bold text-red-400 font-mono text-lg mb-2">> DATA_STREAM::WEAKNESSES</h3>
                <p class="text-red-300/80 whitespace-pre-wrap">{{ $entity->weaknesses ?? 'None Documented.' }}</p>
            </div>
        </div>

        {{-- Kolom Kanan: Galeri Gambar --}}
        <div class="lg:col-span-1">
            <h3 class="font-bold text-primary font-mono text-lg mb-2 text-center">> VISUAL_ARCHIVES</h3>
            @if($entity->images->isNotEmpty())
                <div class="space-y-4">
                    @foreach($entity->images as $image)
                        @php
                            $imagePath = Illuminate\Support\Str::startsWith($image->path, 'http')
                                ? $image->path
                                : asset('uploads/' . $image->path);
                        @endphp
                        <div>
                            <img src="{{ $imagePath }}" alt="{{ $image->caption ?? 'Entity Image' }}" class="w-full h-auto object-cover border-2 border-border-color hover:border-primary transition-all duration-200">
                            @if($image->caption)
                                <p class="text-xs text-secondary mt-2 text-center bg-surface py-1 font-mono">{{ $image->caption }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="w-full h-48 bg-surface flex items-center justify-center border-2 border-dashed border-border-color mt-2">
                    <span class="text-secondary font-mono text-lg">[NO VISUAL DATA AVAILABLE]</span>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
