{{-- ============================================================
     MOBILE VIEW (CARDS) — FAVORITE ARCHIVES
     ------------------------------------------------------------
     Kartu dengan cover besar, badge GAME (amber) vs STANDARD (rose),
     tanggal disimpan relatif, dan tombol unfavorite.
     ============================================================ --}}
<div class="md:hidden space-y-4" id="favorite-card-view">
    @forelse ($favorites as $archive)
        @php
            $isGame = str_contains(
                strtolower(($archive->category ?? '') . ' ' . ($archive->category_other ?? '')),
                'game',
            );
        @endphp

        <div data-favorite-item
            class="relative flex flex-col overflow-hidden rounded-md border transition-all duration-200 hover:-translate-y-0.5
                {{ $isGame
                    ? 'border-amber-500/40 bg-amber-500/[0.03] hover:border-amber-400/70 hover:shadow-[0_0_20px_rgba(245,158,11,0.18)]'
                    : 'border-rose-500/30 bg-surface hover:border-rose-400/60 hover:shadow-[0_0_20px_rgba(244,63,94,0.16)]' }}">

            {{-- Cover --}}
            <div class="relative h-44 overflow-hidden border-b bg-black/50 {{ $isGame ? 'border-amber-500/30' : 'border-rose-500/25' }}"
                x-data="archiveThumb({
                    src: @js($archive->preview_image_url),
                    type: @js($archive->type),
                    name: @js($archive->name),
                })">

                {{-- Fallback icon --}}
                <div x-show="showFallback" x-cloak
                    class="absolute inset-0 flex flex-col items-center justify-center gap-2 {{ $isGame ? 'text-amber-400/35' : 'text-rose-300/30' }}">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path x-bind:d="fallbackIcon" />
                    </svg>
                    <span class="font-mono text-[10px] uppercase tracking-widest">NO_PREVIEW</span>
                </div>

                {{-- Shimmer --}}
                <div x-show="loading && !failed" x-cloak class="absolute inset-0 overflow-hidden">
                    <div
                        class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent animate-[shimmer_1.4s_infinite]">
                    </div>
                </div>

                {{-- Gambar --}}
                <img x-show="showImage" x-cloak :src="src" :alt="name" loading="lazy" decoding="async"
                    class="absolute inset-0 w-full h-full object-cover" onerror="this.style.display='none'" />
                <div x-show="showImage" x-cloak
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none">
                </div>

                {{-- Badge kiri atas --}}
                <div class="absolute left-3 top-3 flex items-center gap-2">
                    @if ($isGame)
                        <span
                            class="inline-flex items-center gap-1 rounded border border-amber-400/50 bg-black/70 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-300 backdrop-blur-sm">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12h4m-2-2v4m7-4h.01M17 14h.01M17.32 5H6.68a4 4 0 00-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 003 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 019.828 16h4.344a2 2 0 011.414.586L17 18c.5.5 1 1 2 1a3 3 0 003-3c0-1.545-.604-6.584-.685-7.258a4 4 0 00-3.995-3.742z" />
                            </svg>
                            GAME DATA
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 rounded border border-rose-400/40 bg-black/70 px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-rose-300 backdrop-blur-sm">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                            </svg>
                            STANDARD
                        </span>
                    @endif
                </div>

                {{-- Jumlah favorit kanan atas --}}
                <span
                    class="absolute right-3 top-3 inline-flex items-center gap-1 rounded border border-rose-400/40 bg-black/70 px-2 py-1 text-[10px] font-mono text-rose-300 backdrop-blur-sm"
                    title="{{ $archive->favorited_by_count }} agent memfavoritkan arsip ini">
                    <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>
                    {{ $archive->favorited_by_count }}
                </span>

                {{-- Kategori di bawah cover --}}
                <span
                    class="absolute bottom-3 left-3 inline-flex items-center rounded px-2 py-0.5 text-[10px] font-mono uppercase tracking-wide backdrop-blur-sm border
                        {{ $isGame
                            ? 'bg-amber-500/20 border-amber-400/40 text-amber-200'
                            : 'bg-rose-500/15 border-rose-400/30 text-rose-200' }}">
                    {{ $archive->category === 'Other' ? $archive->category_other : $archive->category }}
                </span>
            </div>

            {{-- Body --}}
            <div class="flex flex-col gap-3 p-4 font-mono">
                <div class="flex items-start justify-between gap-2">
                    <h2
                        class="text-lg font-bold break-all {{ $isGame ? 'text-amber-300' : 'text-rose-100' }}">
                        {{ $archive->name }}
                    </h2>
                    @if ($archive->is_public)
                        <span
                            class="shrink-0 rounded-full bg-green-900/50 px-2 py-0.5 text-[10px] font-bold text-green-300 border border-green-500/30">PUB</span>
                    @else
                        <span
                            class="shrink-0 rounded-full bg-red-900/50 px-2 py-0.5 text-[10px] font-bold text-red-300 border border-red-500/30">PVT</span>
                    @endif
                </div>

                <div class="space-y-1 text-sm text-secondary">
                    <p>&gt; Owner:
                        @if ($archive->user)
                            <span class="text-secondary">{{ $archive->user->name }}</span>
                        @else
                            <span class="text-red-400 text-xs">[DELETED USER]</span>
                        @endif
                    </p>
                    <p>&gt; Type: <span class="{{ $isGame ? 'text-amber-400' : 'text-rose-300' }}">{{ strtoupper($archive->type) }}</span></p>
                    <p>&gt; Saved:
                        <span class="text-rose-200">{{ $archive->pivot->created_at->diffForHumans() }}</span>
                    </p>
                    <p class="pt-1 text-secondary/80">&gt; Desc:
                        {{ Str::words($archive->description ?? 'N/A', 12) }}
                    </p>
                </div>

                @if ($archive->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($archive->tags as $tag)
                            <span
                                class="rounded-full border px-2 py-0.5 text-[10px] uppercase
                                    {{ $isGame
                                        ? 'bg-amber-500/10 border-amber-500/25 text-amber-400/90'
                                        : 'bg-rose-500/10 border-rose-500/25 text-rose-300/80' }}">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="mt-auto flex items-center justify-between border-t px-4 py-3 {{ $isGame ? 'border-amber-500/25' : 'border-rose-500/20' }}">
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
                }">
                    <button type="button" :disabled="busy"
                        class="flex items-center gap-1.5 rounded border border-rose-500/40 bg-rose-500/10 px-2.5 py-1.5 text-xs font-bold text-rose-300 hover:bg-rose-500/20 hover:text-rose-200 transition-colors disabled:opacity-40 cursor-pointer"
                        @click="unfavorite()">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                        </svg>
                        UNFAV
                    </button>
                </div>

                <a href="{{ route('archives.show', $archive) }}?return_url={{ urlencode(\App\Support\ArchiveReturnUrl::index()) }}"
                    class="inline-flex items-center gap-1.5 rounded bg-rose-500/15 px-3 py-1.5 text-xs font-bold text-rose-200 border border-rose-400/30 hover:bg-rose-500/25 hover:text-white transition-colors">
                    OPEN
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    @empty
        <div class="rounded-md border border-dashed border-rose-500/40 bg-rose-500/[0.03] p-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-dashed border-rose-500/40 bg-rose-500/5">
                <svg class="h-7 w-7 text-rose-400/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <p class="mb-4 font-mono text-sm text-secondary">// NO_FAVORITE_SIGNAL_FOUND //</p>
            <x-button href="{{ route('archives.index') }}">Browse Archives</x-button>
        </div>
    @endforelse
</div>
