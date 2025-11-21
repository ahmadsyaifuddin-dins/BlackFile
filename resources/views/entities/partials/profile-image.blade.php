<div class="bg-black border-2 border-gray-800 p-1 relative group">
    <div class="absolute top-0 left-0 bg-primary text-black text-[10px] font-bold px-2 py-1">PRIMARY
        VISUAL</div>
    <div class="aspect-[3/4] w-full overflow-hidden bg-gray-900 flex items-center justify-center">
        @if ($mainImage)
            @php
                $mainPath = Illuminate\Support\Str::startsWith($mainImage->path, 'http')
                    ? $mainImage->path
                    : asset('uploads/' . $mainImage->path);
            @endphp
            <img src="{{ $mainPath }}"
                class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-500">
        @else
            <div class="text-center opacity-30">
                <svg class="w-20 h-20 mx-auto text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs tracking-widest block mt-2">NO VISUAL</span>
            </div>
        @endif
    </div>
</div>
