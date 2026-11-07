<x-app-layout title="Favorite Archives">
    @once
        @push('styles')
            <style>
                /* Denyut halus untuk ikon hati di hero favorit */
                @keyframes favoritePulse {
                    0%, 100% { transform: scale(1); opacity: 1; }
                    50%      { transform: scale(1.14); opacity: .82; }
                }
                .favorite-heart { animation: favoritePulse 2.4s ease-in-out infinite; }

                /* Garis pemindai tipis di panel hero */
                @keyframes favoriteScan {
                    0%   { transform: translateX(-100%); }
                    100% { transform: translateX(100%); }
                }
                .favorite-scan { animation: favoriteScan 4.5s linear infinite; }
            </style>
        @endpush
    @endonce

    @php
        $stats = $favoriteStats ?? [
            'total' => $favorites->total(),
            'game' => 0,
            'other' => $favorites->total(),
            'public' => 0,
            'private' => 0,
            'last_saved' => null,
        ];
        $lastSaved = $stats['last_saved']
            ? \Illuminate\Support\Carbon::parse($stats['last_saved'])
            : null;
        $hasFilter = request()->hasAny(['search', 'category', 'type']);
    @endphp

    <div class="space-y-6"
        x-data="{
            total: {{ (int) ($stats['total'] ?? 0) }},
            game: {{ (int) ($stats['game'] ?? 0) }},
            other: {{ (int) ($stats['other'] ?? 0) }},
            public: {{ (int) ($stats['public'] ?? 0) }},
            private: {{ (int) ($stats['private'] ?? 0) }},
            onFavoriteRemoved(event) {
                const detail = event.detail || {};
                this.total = Math.max(0, this.total - 1);
                if (detail.game) {
                    this.game = Math.max(0, this.game - 1);
                } else {
                    this.other = Math.max(0, this.other - 1);
                }
                if (detail.public) {
                    this.public = Math.max(0, this.public - 1);
                } else {
                    this.private = Math.max(0, this.private - 1);
                }
            }
        }"
        x-on:favorite-removed.window="onFavoriteRemoved($event)">

        {{-- ============================================================
             HERO — pembeda utama dari halaman ARCHIVES_VAULT biasa
             ============================================================ --}}
        <div class="relative overflow-hidden rounded-md border border-rose-500/30 bg-gradient-to-br from-rose-950/50 via-surface to-surface p-5 sm:p-6">
            {{-- Glow dekoratif --}}
            <div class="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full bg-rose-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-10 h-48 w-48 rounded-full bg-amber-500/5 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px overflow-hidden bg-rose-500/20">
                <div class="favorite-scan h-px w-1/3 bg-gradient-to-r from-transparent via-rose-400 to-transparent"></div>
            </div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    {{-- Ikon hati --}}
                    <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-md border border-rose-500/40 bg-rose-500/10">
                        <svg class="favorite-heart h-6 w-6 fill-current text-rose-400" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                        </svg>
                        <span class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-rose-400">
                            <span class="absolute inset-0 animate-ping rounded-full bg-rose-400/70"></span>
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.35em] text-rose-400/80">
                            <span class="h-px w-6 bg-rose-400/50"></span>
                            priority vault
                        </div>
                        <h1 class="mt-1.5 text-xl font-bold text-rose-300 sm:text-2xl [text-shadow:0_0_16px_rgba(244,63,94,0.35)]">
                            [ FAVORITE_ARCHIVES ]
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-xs leading-relaxed text-secondary sm:text-sm">
                            Koleksi sinyal yang Anda tandai untuk akses cepat. Entri bertanda
                            <span class="font-bold text-amber-300">GAME</span> memiliki warna khusus agar
                            langsung terbedakan dari arsip biasa.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full border border-rose-500/30 bg-black/30 px-3 py-1 text-[10px] font-mono uppercase tracking-widest text-rose-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                        synced
                    </span>
                    <x-button variant="outline" href="{{ route('archives.index') }}">
                        &lt;-- Back to Vault
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="rounded-md border border-rose-500/40 bg-surface-light px-4 py-3 font-mono text-sm text-rose-200">
                <span class="font-bold">&gt; Status:</span> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-md border border-red-500 bg-surface-light px-4 py-3 font-mono text-sm text-red-500">
                <span class="font-bold">&gt; Error:</span> {{ session('error') }}
            </div>
        @endif

        {{-- ============================================================
             RINGKASAN KOLEKSI (live counter, ikut filter aktif)
             ============================================================ --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
            {{-- Total --}}
            <div class="relative overflow-hidden rounded-md border border-rose-500/30 bg-surface p-3 sm:p-4">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-rose-400 to-transparent"></div>
                <p class="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-secondary">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span> total saved
                </p>
                <p class="mt-2 text-2xl font-bold text-rose-200" x-text="total">{{ $stats['total'] }}</p>
                <p class="mt-1 text-[10px] text-secondary/70">
                    {{ $hasFilter ? 'hasil filter aktif' : 'seluruh koleksi favorit' }}
                </p>
            </div>

            {{-- Game --}}
            <div class="rounded-md border border-amber-500/35 bg-amber-500/[0.04] p-3 sm:p-4">
                <p class="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-amber-300/90">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 12h4m-2-2v4m7-4h.01M17 14h.01M17.32 5H6.68a4 4 0 00-3.978 3.59c-.006.052-.01.101-.017.152C2.604 9.416 2 14.456 2 16a3 3 0 003 3c1 0 1.5-.5 2-1l1.414-1.414A2 2 0 019.828 16h4.344a2 2 0 011.414.586L17 18c.5.5 1 1 2 1a3 3 0 003-3c0-1.545-.604-6.584-.685-7.258a4 4 0 00-3.995-3.742z" />
                    </svg>
                    game data
                </p>
                <p class="mt-2 text-2xl font-bold text-amber-300" x-text="game">{{ $stats['game'] }}</p>
                <p class="mt-1 text-[10px] text-amber-300/50">aksen amber khusus</p>
            </div>

            {{-- Non-game --}}
            <div class="rounded-md border border-rose-500/25 bg-surface p-3 sm:p-4">
                <p class="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-rose-300/80">
                    <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.672l1.318-1.354a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>
                    standard
                </p>
                <p class="mt-2 text-2xl font-bold text-rose-200" x-text="other">{{ $stats['other'] }}</p>
                <p class="mt-1 text-[10px] text-secondary/70">arsip non-game</p>
            </div>

            {{-- Visibility --}}
            <div class="rounded-md border border-green-500/25 bg-surface p-3 sm:p-4">
                <p class="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-secondary">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span> visibility
                </p>
                <p class="mt-2 flex items-baseline gap-2 text-sm font-bold">
                    <span class="text-green-400"><span x-text="public">{{ $stats['public'] }}</span> PUB</span>
                    <span class="text-secondary/40">/</span>
                    <span class="text-red-400"><span x-text="private">{{ $stats['private'] }}</span> PVT</span>
                </p>
                <p class="mt-1 text-[10px] text-secondary/70">status akses arsip</p>
            </div>

            {{-- Last saved --}}
            <div class="col-span-2 rounded-md border border-rose-500/25 bg-surface p-3 sm:p-4 lg:col-span-1">
                <p class="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-secondary">
                    <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span> last saved
                </p>
                @if ($lastSaved)
                    <p class="mt-2 text-sm font-bold text-cyan-300" title="{{ $lastSaved->translatedFormat('d F Y, H:i') }}">
                        {{ $lastSaved->diffForHumans() }}
                    </p>
                    <p class="mt-1 text-[10px] text-secondary/70">{{ $lastSaved->format('d M Y, H:i') }}</p>
                @else
                    <p class="mt-2 text-sm font-bold text-secondary/50">---</p>
                    <p class="mt-1 text-[10px] text-secondary/70">belum ada favorit</p>
                @endif
            </div>
        </div>

        {{-- Filter (menggunakan partial yang sama dengan archive index) --}}
        @include('archives.partials._filter-form', [
            'searchRoute' => route('favorites.archives'),
            'categories' => $categories,
            'accent' => 'rose',
        ])

        {{-- Legenda warna --}}
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 font-mono text-[10px] uppercase tracking-widest text-secondary/70">
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-amber-400"></span> game data
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-rose-400"></span> standard favorite
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-green-400"></span> public
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-red-400"></span> private
            </span>
            <span class="ml-auto hidden sm:inline">&gt; klik hati untuk melepas favorite</span>
        </div>

        {{-- Desktop: tabel --}}
        @include('archives.partials._favorite-table-view', ['favorites' => $favorites])

        {{-- Mobile: kartu --}}
        @include('archives.partials._favorite-card-view', ['favorites' => $favorites])

        {{-- Paginasi --}}
        @if ($favorites->hasPages())
            <div class="rounded-md border border-rose-500/25 bg-surface p-4">
                {{ $favorites->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
