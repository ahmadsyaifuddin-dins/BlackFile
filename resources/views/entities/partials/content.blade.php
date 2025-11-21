<div class="lg:col-span-8 space-y-8">

    {{-- 1. DESCRIPTION --}}
    @include('entities.partials.description', ['entity' => $entity])

    {{-- 2. ABILITIES & WEAKNESSES (GRID 2 COLS) --}}
    @include('entities.partials.abilities-weaknesses', ['entity' => $entity])

    {{-- 3. EVIDENCE GALLERY (GRID) --}}
    @if ($galleryImages->isNotEmpty())
        @include('entities.partials.gallery', ['galleryImages' => $galleryImages])
    @endif

</div>
