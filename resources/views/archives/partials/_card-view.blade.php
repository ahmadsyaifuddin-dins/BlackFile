{{-- MOBILE VIEW (CARDS) --}}
<div class="md:hidden space-y-4" id="archive-card-view">
    @forelse ($archives as $archive)
        @php $isFocus = ($focusId ?? null) === $archive->id; @endphp
        {{-- Menambahkan flex-col dan h-full untuk struktur yang lebih baik --}}
        <div id="archive-row-{{ $archive->id }}"
            @if ($isFocus) data-focus-row @endif
            @class([
                'bg-surface border border-green-500/50 rounded-md p-4 flex flex-col h-full',
                'archive-focus-card' => $isFocus,
            ])>
            {{-- Cover: gambar arsip jika ada, ikon default besar jika tidak --}}
            <div class="relative -mx-4 -mt-4 mb-3 h-36 overflow-hidden rounded-t-md border-b border-green-500/30 bg-black/50"
                x-data="archiveThumb({
                    src: @js($archive->preview_image_url),
                    type: @js($archive->type),
                    name: @js($archive->name),
                })">
                {{-- Ikon fallback --}}
                <div x-show="showFallback" x-cloak
                    class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-primary/35">
                    <svg class="w-11 h-11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path x-bind:d="fallbackIcon" />
                    </svg>
                    <span class="font-mono text-[10px] uppercase tracking-widest text-primary/30">
                        NO_PREVIEW
                    </span>
                </div>

                {{-- Shimmer selagi dimuat --}}
                <div x-show="loading && !failed" x-cloak class="absolute inset-0 overflow-hidden">
                    <div
                        class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-primary/10 to-transparent animate-[shimmer_1.4s_infinite]">
                    </div>
                </div>

                {{-- Gambar: object-cover supaya proporsinya rapi, gradien di bawah biar teks tag tetap terbaca --}}
                <img x-show="showImage" x-cloak :src="src" :alt="name" loading="lazy" decoding="async"
                    class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none'" />
                <div x-show="showImage" x-cloak
                    class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent pointer-events-none">
                </div>
            </div>

            {{-- Baris Header Kartu --}}
            <div class="flex justify-between items-start gap-2">
                <h2 class="font-bold text-primary break-all pr-2">{{ $archive->name }}</h2>
                <div class="flex-shrink-0">
                    @if ($archive->is_public)
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/50 text-green-300">Public</span>
                    @else
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/50 text-red-300">Private</span>
                    @endif
                </div>
            </div>

            {{-- flex-grow akan mendorong footer ke bawah --}}
            <div class="text-sm text-secondary space-y-1 mt-3 flex-grow">
                <p><span class="font-semibold">Owner:</span>
                    @if ($archive->user)
                        {{ $archive->user->name }}
                    @else
                        <span class="text-red-400 font-mono text-xs"
                            title="Pemilik arsip ini sudah tidak ada di sistem">[DELETED USER]</span>
                    @endif
                </p>
                <p><span class="font-semibold">Type:</span> {{ $archive->type }}</p>
                <p><span class="font-semibold">Category:</span>
                    {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                </p>
                <p><span class="font-semibold">Added:</span> {{ $archive->created_at->translatedFormat('d M Y, H:i') }}
                </p>
                <p class="pt-1 break-words"><span class="font-semibold">Desc:</span>
                    {{ Str::words($archive->description ?? 'N/A', 10) }}</p>
            </div>

            {{-- Footer Kartu untuk Tags dan Aksi --}}
            <div class="mt-4 pt-3 border-t border-green-500/50">
                {{-- Bagian Tags --}}
                @if ($archive->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach ($archive->tags as $tag)
                            <span
                                class="px-2 py-0.5 text-xs rounded-full bg-surface-light text-green-500/50">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Bagian Aksi --}}
                <div class="flex items-center justify-between">
                    <div x-data="{
                        isFavorited: {{ $archive->is_favorited ? 'true' : 'false' }},
                        count: {{ $archive->favorited_by_count }}
                    }" class="inline-flex items-center gap-1">
                        <button class="cursor-pointer"
                            @click="
                                axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                    .then(response => {
                                        isFavorited = response.data.is_favorited;
                                        count = response.data.favorited_by_count;
                                    });
                            ">
                            <svg class="h-5 w-5 transition-colors duration-200"
                                :class="isFavorited ? 'text-red-500 fill-current' : 'text-secondary hover:text-red-400'"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                            </svg>
                        </button>
                        <span x-text="count" class="text-sm text-secondary"></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('archives.show', $archive) }}?return_url={{ urlencode(\App\Support\ArchiveReturnUrl::index()) }}"
                            class="text-secondary text-primary-hover text-sm">Details</a>
                        <a href="{{ route('archives.edit', $archive) }}?return_url={{ urlencode(\App\Support\ArchiveReturnUrl::index()) }}"
                            class="text-yellow-500 hover:text-yellow-400 text-sm">Edit</a>
                        @can('delete', $archive)
                            <x-button.delete
                                :action="route('archives.destroy', $archive).'?return_url='.urlencode(\App\Support\ArchiveReturnUrl::index())"
                                title="TERMINATE ARCHIVE?"
                                message="{{ $archive->user_id === auth()->id() ? 'Confirm termination of this entry?' : 'Hapus arsip milik Agent lain? Tindakan ini tercatat sebagai override Director.' }}"
                                target="{{ $archive->name }}">
                                Delete
                            </x-button.delete>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-surface border border-border rounded-md p-4 text-center text-sm text-secondary">
            // NO_DATA_ENTRY_FOUND //
        </div>
    @endforelse
</div>
