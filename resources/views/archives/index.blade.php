<x-app-layout title="Archives Vault">
    {{-- [BARU] Highlight + auto-scroll ke baris arsip yang baru diedit --}}
    @once
        @push('styles')
            <style>
                .archive-focus-row {
                    background-color: rgba(234, 179, 8, .12) !important;
                    box-shadow: inset 3px 0 0 0 #eab308;
                    animation: archiveFocusPulse 1.6s ease-in-out 3;
                }
                .archive-focus-card {
                    border-color: #eab308 !important;
                    box-shadow: 0 0 18px rgba(234, 179, 8, .28);
                    animation: archiveFocusPulse 1.6s ease-in-out 3;
                }
                @keyframes archiveFocusPulse {
                    0%, 100% { background-color: rgba(234, 179, 8, .12); }
                    50%      { background-color: rgba(234, 179, 8, .28); }
                }
                /* Shimmer untuk thumbnail yang masih dimuat */
                @keyframes shimmer {
                    100% { transform: translateX(100%); }
                }
                /* Hilangkan elemen Alpine sebelum JS siap agar tidak "berkedip" */
                [x-cloak] { display: none !important; }
            </style>
        @endpush
    @endonce

    <div class="space-y-6"
        x-data="{
            init() {
                this.$nextTick(() => {
                    const row = document.querySelector('[data-focus-row]');
                    if (!row) return;
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        }">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl text-glow font-bold text-primary">[ ARCHIVES_VAULT ]</h1>
            {{-- Bawa posisi user (filter + halaman + urutan) ke form tambah, supaya
                submit-nya bisa balik ke tempat yang sama --}}
            <x-button variant="outline"
                href="{{ route('archives.create') }}?return_url={{ urlencode(\App\Support\ArchiveReturnUrl::index()) }}">
                + ADD_NEW_ENTRY
            </x-button>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="px-4 py-3 border rounded-md bg-surface-light border-primary text-primary">
                <span class="font-bold">> Status:</span> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="px-4 py-3 border rounded-md bg-surface-light border-red-500 text-red-500">
                <span class="font-bold">> Status:</span> {{ session('error') }}
            </div>
        @endif

        {{-- [BARU] Arsip yang diedit sudah tidak cocok dengan filter aktif.
             Penandanya lewat query string `focus_miss`, bukan session, supaya
             tidak muncul lagi di request berikutnya. --}}
        @if (request('focus_miss'))
            <div x-data="{ show: true }" x-show="show" x-cloak
                x-init="setTimeout(() => show = false, 6000)"
                class="px-4 py-3 border rounded-md bg-yellow-900/20 border-yellow-600 text-yellow-400 font-mono text-sm"
                role="status">
                <span class="font-bold">&gt; NOTICE:</span>
                Arsip yang baru saja diperbarui tidak lagi cocok dengan filter aktif dan tidak muncul di halaman ini.
            </div>
            @once
                @push('scripts')
                    <script>
                        window.addEventListener('DOMContentLoaded', () => {
                            window.agentAlert?.(
                                'warning',
                                'ENTRY OUT OF FILTER SCOPE',
                                'Arsip yang baru saja diedit tidak lagi cocok dengan filter aktif, sehingga tidak ditemukan pada halaman ini.'
                            );
                        });
                    </script>
                @endpush
            @endonce
        @endif

        {{-- Panggil Komponen Filter --}}
        @include('archives.partials._filter-form', ['searchRoute' => route('archives.index')])

        {{-- Panggil Komponen Tabel untuk Desktop --}}
        <div class="hidden md:block">
            @include('archives.partials._table-view', ['archives' => $archives])
        </div>

        {{-- Panggil Komponen Kartu untuk Mobile --}}
        <div class="md:hidden">
            @include('archives.partials._card-view', ['archives' => $archives])
        </div>

        {{-- Paginasi --}}
        @if ($archives->hasPages())
            <div class="p-4 bg-surface rounded-md">
                {{ $archives->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
