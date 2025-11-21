@php
    $origin = request('return_url') ?? null;
    $backToIndex = $origin ?? route('entities.index');
    $editUrl = route('entities.edit', $entity) . '?return_url=' . urlencode(route('entities.show', $entity));
    if ($origin) {
        $editUrl .= '&origin=' . urlencode($origin);
    }

    // Logic Gambar Utama (Thumbnail -> Gambar Pertama -> Placeholder)
    $mainImage = null;
    if ($entity->thumbnail) {
        $mainImage = $entity->thumbnail;
    } elseif ($entity->images->isNotEmpty()) {
        $mainImage = $entity->images->first();
    }

    // Logic Sisa Gambar untuk Galeri (Kecuali gambar utama)
    $galleryImages = $entity->images->reject(function ($img) use ($mainImage) {
        return $mainImage && $img->id === $mainImage->id;
    });
@endphp

<x-app-layout :title="$entity->codename ?? $entity->name">

    <div class="max-w-7xl mx-auto min-h-screen font-mono text-gray-300">

        {{-- HEADER / TITLE BAR --}}
        @include('entities.partials.header', [
            'entity' => $entity,
            'editUrl' => $editUrl,
            'backToIndex' => $backToIndex,
        ])

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- === SIDEBAR (LEFT - 4 COLS) === --}}
            @include('entities.partials.sidebar', [
                'entity' => $entity,
                'mainImage' => $mainImage,
            ])

            {{-- === CONTENT AREA (RIGHT - 8 COLS) === --}}
            @include('entities.partials.content', [
                'entity' => $entity,
                'galleryImages' => $galleryImages,
            ])

        </div>
    </div>

    {{-- CHART SCRIPT --}}
    @include('entities.partials.chart-script')

</x-app-layout>
