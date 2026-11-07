{{-- ============================================================
     DESKTOP VIEW (TABLE) — FAVORITE ARCHIVES
     ------------------------------------------------------------
     Varian khusus halaman favorit:
     - Aksen rose (heart theme) supaya beda dari ARCHIVES_VAULT.
     - Thumbnail di setiap baris (archiveThumb + fallback icon).
     - Data Game (kategori Link Game) memakai aksen amber + badge GAME
       supaya langsung terbedakan dari arsip biasa.
     ============================================================ --}}
<div class="hidden md:block" id="favorite-table-view">
    <div class="bg-surface/50 border border-rose-500/25 rounded-sm overflow-hidden shadow-lg backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-rose-500/15">
                {{-- Header bergaya terminal --}}
                <thead class="bg-black/40 text-rose-300">
                    <tr class="font-mono text-xs tracking-widest">
                        <th scope="col"
                            class="w-16 px-4 py-4 text-left font-bold uppercase border-b border-rose-500/25">
                            <span class="sr-only">Preview</span>
                        </th>
                        <th scope="col" class="px-5 py-4 text-left font-bold uppercase border-b border-rose-500/25">
                            &gt; FILE_NAME &amp; TAGS
                        </th>
                        <th scope="col" class="px-5 py-4 text-left font-bold uppercase border-b border-rose-500/25">
                            OWNER
                        </th>
                        <th scope="col" class="px-5 py-4 text-left font-bold uppercase border-b border-rose-500/25">
                            VISIBILITY
                        </th>
                        <th scope="col" class="px-5 py-4 text-left font-bold uppercase border-b border-rose-500/25">
                            CAT / TYPE
                        </th>
                        <th scope="col" class="px-5 py-4 text-left font-bold uppercase border-b border-rose-500/25">
                            SAVED_AT
                        </th>
                        <th scope="col" class="relative px-5 py-4 border-b border-rose-500/25">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-rose-500/10 bg-transparent">
                    @forelse ($favorites as $archive)
                        @php
                            $isGame = str_contains(
                                strtolower(($archive->category ?? '') . ' ' . ($archive->category_other ?? '')),
                                'game',
                            );
                        @endphp

                        <tr data-favorite-item
                            class="group transition-colors duration-200 {{ $isGame ? 'bg-amber-500/[0.04] hover:bg-amber-500/[0.09]' : 'hover:bg-rose-500/5' }}">

                            {{-- Kolom Preview --}}
                            <td class="px-4 py-4 align-top border-l-2 {{ $isGame ? 'border-l-amber-400' : 'border-l-transparent' }}">
                                <div class="relative w-12 h-12 shrink-0" x-data="archiveThumb({
                                    src: @js($archive->preview_image_url),
                                    type: @js($archive->type),
                                    name: @js($archive->name),
                                })">
                                    {{-- Fallback icon --}}
                                    <div x-show="showFallback" x-cloak
                                        class="absolute inset-0 flex items-center justify-center rounded border transition-colors
                                            {{ $isGame
                                                ? 'border-amber-500/40 bg-amber-950/30 text-amber-400/50 group-hover:text-amber-300/80'
                                                : 'border-rose-500/30 bg-black/40 text-rose-300/40 group-hover:text-rose-200/70' }}">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            aria-hidden="true">
                                            <path x-bind:d="fallbackIcon" />
                                        </svg>
                                    </div>

                                    {{-- Skeleton shimmer --}}
                                    <div x-show="loading && !failed" x-cloak
                                        class="absolute inset-0 rounded border border-rose-500/25 bg-black/40 overflow-hidden">
                                        <div
                                            class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-rose-400/10 to-transparent animate-[shimmer_1.4s_infinite]">
                                        </div>
                                    </div>

                                    {{-- Gambar --}}
                                    <img x-show="showImage" x-cloak :src="src" :alt="name" loading="lazy"
                                        decoding="async"
                                        class="w-full h-full object-cover rounded border transition-colors
                                            {{ $isGame
                                                ? 'border-amber-500/40 bg-amber-950/30 group-hover:border-amber-300/70'
                                                : 'border-rose-500/30 bg-black/40 group-hover:border-rose-300/60' }}"
                                        onerror="this.style.display='none'" />
                                </div>
                            </td>

                            {{-- Kolom Nama & Tags --}}
                            <td class="px-5 py-4 align-top">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="text-sm font-bold transition-all {{ $isGame ? 'text-amber-300 group-hover:[text-shadow:0_0_10px_rgba(251,191,36,0.55)]' : 'text-rose-100 group-hover:[text-shadow:0_0_10px_rgba(251,113,133,0.45)]' }}">
                                        {{ $archive->name }}
                                    </span>

                                    @if ($isGame)
                                        <span
                                            class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-300 border border-amber-400/40">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 12h4m-2-2v4m7-4h.01M17 14h.01M17.32 5H6.68a4 4 0 00-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 003 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 019.828 16h4.344a2 2 0 011.414.586L17 18c.5.5 1 1 2 1a3 3 0 003-3c0-1.545-.604-6.584-.685-7.258a4 4 0 00-3.995-3.742z" />
                                            </svg>
                                            GAME
                                        </span>
                                    @endif
                                </div>

                                @if ($archive->tags->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach ($archive->tags as $tag)
                                            <span
                                                class="px-1.5 py-0.5 text-[10px] uppercase font-mono rounded border
                                                    {{ $isGame
                                                        ? 'bg-amber-500/10 border-amber-500/25 text-amber-400/90'
                                                        : 'bg-rose-500/10 border-rose-500/25 text-rose-300/80' }}">
                                                #{{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            {{-- Owner --}}
                            <td class="px-5 py-4 align-top text-sm font-mono">
                                @if ($archive->user)
                                    <span class="text-secondary">{{ $archive->user->name }}</span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] uppercase bg-red-900/30 text-red-400 border border-red-500/30"
                                        title="Pemilik arsip ini sudah tidak ada di sistem">
                                        [DELETED USER]
                                    </span>
                                @endif
                            </td>

                            {{-- Visibility --}}
                            <td class="px-5 py-4 align-top">
                                @if ($archive->is_public)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-900/30 text-green-400 border border-green-500/30">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full animate-pulse"></span>
                                        PUBLIC
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-900/30 text-red-400 border border-red-500/30">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                                        PRIVATE
                                    </span>
                                @endif
                            </td>

                            {{-- Category & Type --}}
                            <td class="px-5 py-4 align-top text-sm text-secondary">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="inline-flex w-fit items-center gap-1 rounded px-1.5 py-0.5 border text-xs
                                            {{ $isGame
                                                ? 'bg-amber-500/10 border-amber-500/30 text-amber-300'
                                                : 'bg-surface-light border-border-color text-secondary' }}">
                                        {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                                    </span>
                                    <span
                                        class="text-[11px] font-mono opacity-70 {{ $isGame ? 'text-amber-400' : 'text-rose-300' }}">[{{ strtoupper($archive->type) }}]</span>
                                </div>
                            </td>

                            {{-- Saved At (pivot) --}}
                            <td class="px-5 py-4 align-top text-sm font-mono whitespace-nowrap">
                                <span class="text-rose-200"
                                    title="{{ $archive->pivot->created_at->translatedFormat('d F Y, H:i') }}">
                                    {{ $archive->pivot->created_at->diffForHumans() }}
                                </span>
                                <br>
                                <span class="text-[11px] opacity-60 text-secondary">
                                    {{ $archive->pivot->created_at->format('d M Y, H:i') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 align-middle text-right text-sm font-medium">
                                <div
                                    class="flex items-center justify-end gap-3 opacity-70 group-hover:opacity-100 transition-opacity">

                                    {{-- Jumlah total favorit --}}
                                    <span
                                        class="hidden lg:inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] font-mono text-rose-300/80 bg-rose-500/10 border border-rose-500/20"
                                        title="{{ $archive->favorited_by_count }} agent memfavoritkan arsip ini">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                        </svg>
                                        {{ $archive->favorited_by_count }}
                                    </span>

                                    {{-- Unfavorite --}}
                                    <div x-data="{
                                        busy: false,
                                        async unfavorite() {
                                            const confirmed = await window.agentConfirm?.(
                                                'REMOVE FROM FAVORITES?',
                                                'Archive ini akan dihapus dari daftar favorites Anda.',
                                                'REMOVE',
                                                'CANCEL'
                                            );
                                            if (!confirmed || this.busy) return;
                                            this.busy = true;
                                            axios.post('{{ route('archives.favorite.toggle', $archive) }}')
                                                .then(response => {
                                                    if (!response.data.is_favorited) {
                                                        this.$dispatch('favorite-removed', {
                                                            game: {{ $isGame ? 'true' : 'false' }},
                                                            public: {{ $archive->is_public ? 'true' : 'false' }},
                                                        });
                                                        this.$el.closest('[data-favorite-item]')?.remove();
                                                        window.agentAlert?.('success', 'FAVORITE REMOVED', 'Archive dihapus dari daftar favorites.');
                                                    }
                                                })
                                                .catch(() => {
                                                    this.busy = false;
                                                    window.agentAlert?.('error', 'TRANSMISSION FAILED', 'Gagal menghapus archive dari favorites.');
                                                });
                                        }
                                    }" class="inline-flex items-center">
                                        <button type="button" :disabled="busy"
                                            class="cursor-pointer focus:outline-none transform hover:scale-110 transition-transform disabled:opacity-40"
                                            title="Unfavorite" @click="unfavorite()">
                                            <svg class="h-5 w-5 text-rose-400 fill-current hover:text-rose-300 transition-colors"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Details --}}
                                    <a href="{{ route('archives.show', $archive) }}?return_url={{ urlencode(\App\Support\ArchiveReturnUrl::index()) }}"
                                        class="text-cyan-400 hover:text-cyan-300 transform hover:scale-110 transition-transform"
                                        title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center justify-center gap-3 text-secondary/60">
                                    <div
                                        class="flex h-14 w-14 items-center justify-center rounded-full border border-dashed border-rose-500/40 bg-rose-500/5">
                                        <svg class="h-7 w-7 text-rose-400/60" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                        </svg>
                                    </div>
                                    <span class="font-mono text-sm tracking-wider">// NO_FAVORITE_SIGNAL_FOUND //</span>
                                    <x-button href="{{ route('archives.index') }}" size="sm">Browse Archives</x-button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
