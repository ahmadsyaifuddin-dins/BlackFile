{{--
    PANEL STATISTIK VAULT (ARCHIVE INDEX)
    -------------------------------------------------------------
    Accordion hacker-theme: tertutup secara default, bisa dibuka/tutup.
    Semua angka berasal dari query yang SUDAH difilter (vaultStats di
    ArchiveController), jadi card-card-nya ikut menyusut mengikuti filter
    yang sedang aktif (search / category / type / owner).
--}}

@php
    $stats = $vaultStats ?? [];
    $hasFilter = request()->hasAny(['search', 'category', 'type', 'owner']);

    // Format ukuran file (bytes -> human readable) ala terminal
    if (! function_exists('vaultBytes')) {
        function vaultBytes($bytes)
        {
            $bytes = (int) $bytes;
            if ($bytes <= 0) {
                return '0 B';
            }
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = floor(log($bytes, 1024));

            return number_format($bytes / (1024 ** $i), ($i >= 2 ? 2 : 0)) . ' ' . $units[$i];
        }
    }

    $categories = $stats['categories'] ?? [];
@endphp

<div x-data="{ open: false }" class="bg-surface border border-green-500/30 rounded-md p-3 sm:p-4 font-mono">
    {{-- Header accordion (tombol buka/tutup) --}}
    <button type="button" @click="open = !open"
        class="w-full flex items-center justify-between gap-3 text-left focus:outline-none group"
        :aria-expanded="open ? 'true' : 'false'">
        <span class="flex items-center gap-2 text-sm sm:text-base text-glow font-bold tracking-widest">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012-2h2a2 2 0 012 2h2a2 2 0 012 2v2a2 2 0 012 2v2a2 2 0 01-2 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2a2 2 0 01-2-2v-2a2 2 0 012-2v-2a2 2 0 011-2zm6 8a3 3 0 100-6 3 3 0 000 6z" />
            </svg>
            [ VAULT_STATS ]
            @if ($hasFilter)
                <span class="hidden sm:inline-block px-2 py-0.5 text-[10px] text-black bg-green-400 rounded-full font-bold tracking-wider">
                    FILTERED
                </span>
            @endif
        </span>
        <span class="flex items-center gap-2 text-primary">
            <span x-show="!open" x-cloak class="hover:opacity-75">[ BUKA ]</span>
            <span x-show="open" x-cloak class="hover:opacity-75">[ TUTUP ]</span>
            <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </span>
    </button>

    {{-- Isi accordion (card-card statistik) --}}
    <div x-show="open" x-transition.opacity.duration.250ms x-cloak>
        <div class="mt-4 pt-4 border-t border-dashed border-green-500/20">

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">

                {{-- TOTAL ARCHIVES (card utama) --}}
                <div class="relative overflow-hidden bg-surface-light border border-green-500/40 rounded-md p-3 sm:p-4 col-span-2 md:col-span-3 xl:col-span-4">
                    <div class="absolute top-0 left-0 w-full h-0.5 bg-gradient-to-r from-transparent via-green-400 to-transparent"></div>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] sm:text-xs text-secondary uppercase tracking-widest">Total Archives <span class="text-secondary/50">(filter aktif)</span></p>
                            <p class="mt-1 text-2xl sm:text-3xl font-bold text-glow">{{ number_format($stats['total'] ?? 0) }}</p>
                        </div>
                        <div class="text-right text-[11px] sm:text-xs text-secondary space-y-1">
                            <p class="flex items-center justify-end gap-1">
                                <span class="w-2 h-2 inline-block bg-green-400 rounded-full"></span>
                                PUBLIC {{ number_format($stats['visibility']['public'] ?? 0) }}
                            </p>
                            <p class="flex items-center justify-end gap-1">
                                <span class="w-2 h-2 inline-block bg-gray-500 rounded-full"></span>
                                PRIVATE {{ number_format($stats['visibility']['private'] ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BY TYPE : FILE --}}
                <div class="bg-surface-light border border-green-500/25 rounded-md p-3 sm:p-4 group hover:border-green-400/60 transition-colors">
                    <p class="text-[11px] sm:text-xs text-secondary uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> File
                    </p>
                    <p class="mt-2 text-xl sm:text-2xl font-bold text-primary">{{ number_format($stats['type']['file'] ?? 0) }}</p>
                    <p class="mt-1 text-[10px] sm:text-[11px] text-secondary/70">
                        {{ vaultBytes($stats['files_total_size'] ?? 0) }} total
                    </p>
                </div>

                {{-- BY TYPE : URL --}}
                <div class="bg-surface-light border border-green-500/25 rounded-md p-3 sm:p-4 group hover:border-green-400/60 transition-colors">
                    <p class="text-[11px] sm:text-xs text-secondary uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span> URL / Link
                    </p>
                    <p class="mt-2 text-xl sm:text-2xl font-bold text-primary">{{ number_format($stats['type']['url'] ?? 0) }}</p>
                    <p class="mt-1 text-[10px] sm:text-[11px] text-secondary/70">external entry</p>
                </div>

                {{-- JUMLAH TAG UNIK --}}
                <div class="bg-surface-light border border-green-500/25 rounded-md p-3 sm:p-4 group hover:border-green-400/60 transition-colors">
                    <p class="text-[11px] sm:text-xs text-secondary uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> Tags
                    </p>
                    <p class="mt-2 text-xl sm:text-2xl font-bold text-primary">{{ number_format($stats['tags'] ?? 0) }}</p>
                    <p class="mt-1 text-[10px] sm:text-[11px] text-secondary/70">unique tags</p>
                </div>

                {{-- JUMLAH OWNER --}}
                <div class="bg-surface-light border border-green-500/25 rounded-md p-3 sm:p-4 group hover:border-green-400/60 transition-colors">
                    <p class="text-[11px] sm:text-xs text-secondary uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span> Agents
                    </p>
                    <p class="mt-2 text-xl sm:text-2xl font-bold text-primary">{{ number_format($stats['owners'] ?? 0) }}</p>
                    <p class="mt-1 text-[10px] sm:text-[11px] text-secondary/70">unique owners</p>
                </div>

                {{-- BREAKDOWN KATEGORI (kartu lebar) --}}
                <div class="bg-surface-light border border-green-500/25 rounded-md p-3 sm:p-4 col-span-2 md:col-span-3 xl:col-span-2 group hover:border-green-400/60 transition-colors">
                    <p class="text-[11px] sm:text-xs text-secondary uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Top Categories
                    </p>
                    @if (count($categories) > 0)
                        <ul class="mt-2 space-y-1.5">
                            @foreach ($categories as $label => $count)
                                <li class="flex items-center justify-between gap-3 text-xs sm:text-sm">
                                    <span class="truncate text-secondary">{{ $label }}</span>
                                    <span class="shrink-0 text-primary font-bold">{{ number_format($count) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-xs sm:text-sm text-secondary/50">--- no data ---</p>
                    @endif
                </div>

            </div>

            {{-- Keterangan filter --}}
            @if ($hasFilter)
                <p class="mt-3 text-[10px] sm:text-[11px] text-secondary/60 tracking-wide">
                    &gt; Angka di atas mencerminkan hasil filter yang sedang aktif.
                </p>
            @else
                <p class="mt-3 text-[10px] sm:text-[11px] text-secondary/60 tracking-wide">
                    &gt; Menampilkan ringkasan seluruh vault.
                </p>
            @endif
        </div>
    </div>
</div>