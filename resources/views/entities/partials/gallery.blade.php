<div>
    <div class="flex items-center gap-2 mb-4 border-b border-gray-800 pb-2">
        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="text-gray-500 font-bold tracking-widest text-xs uppercase">SUPPLEMENTARY
            VISUAL
            EVIDENCE</h3>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($galleryImages as $image)
            <div class="group relative aspect-square bg-black border border-gray-800 overflow-hidden cursor-pointer">
                {{-- CUKUP PANGGIL ->url --}}
                <img src="{{ $image->url }}"
                    class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-all duration-500 group-hover:scale-110">

                @if ($image->caption)
                    <div
                        class="absolute bottom-0 inset-x-0 bg-black/80 text-[10px] text-gray-400 p-1 text-center translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        {{ Str::limit($image->caption, 20) }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
