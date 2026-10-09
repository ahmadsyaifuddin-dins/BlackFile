@props([
    'archive',
    'compact' => false, // true = cocok untuk cell tabel; false = panel untuk show page
])

@php
    $toggleShareUrl = route('archives.toggle_share', $archive);
    $toggleAdUrl = route('archives.toggle_ad', $archive);
    $publicUrl = $archive->publicUrl();
@endphp

<div
    x-data="{
        innerPublic: {{ $archive->is_public ? 'true' : 'false' }},   // visibilitas INTERNAL (is_public)
        isShared: {{ $archive->is_shared ? 'true' : 'false' }},      // link EKSTERNAL (is_shared)
        hasAd: {{ $archive->has_ad ? 'true' : 'false' }},
        publicUrl: @js($publicUrl),
        stateUrl: null,
        copied: false,
        busy: false,

        async toggleShare() {
            if (this.busy) return;
            this.busy = true;
            try {
                const { data } = await axios.post(@js($toggleShareUrl));
                this.innerPublic = data.is_public;
                this.isShared = data.is_shared;
                this.hasAd = data.has_ad;
                this.publicUrl = data.public_url;
            } catch (e) {
                console.error(e);
                if (e.response?.status === 409) {
                    window.agentAlert?.('error', 'LINK BLOCKED', 'Visibilitas internal masih private. Aktifkan status internal PUBLIC dulu.');
                }
            } finally {
                this.busy = false;
            }
        },

        async toggleAd() {
            if (this.busy) return;
            this.busy = true;
            try {
                const { data } = await axios.post(@js($toggleAdUrl));
                this.innerPublic = data.is_public;
                this.isShared = data.is_shared;
                this.hasAd = data.has_ad;
                this.publicUrl = data.public_url;
            } catch (e) {
                console.error(e);
            } finally {
                this.busy = false;
            }
        },

        async copyLink() {
            if (!this.publicUrl) return;
            try {
                await navigator.clipboard.writeText(this.publicUrl);
                this.copied = true;
                setTimeout(() => this.copied = false, 1500);
            } catch (e) {
                console.error(e);
            }
        }
    }"
    class="{{ $compact ? '' : 'space-y-3' }}">

    @if ($compact)
        {{-- COMPACT: untuk tabel/kartu.
             Badge PUBLIC/PRIVATE = status INTERNAL (is_public, diatur di form).
             Poros SHARE_LINK = link EKSTERNAL; mati/terkunci bila internal private. --}}
        <div class="flex flex-col items-start gap-1.5">
            {{-- Status internal --}}
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border"
                :class="innerPublic
                    ? 'bg-green-900/30 text-green-400 border-green-500/30'
                    : 'bg-red-900/30 text-red-400 border-red-500/30'"
                :title="innerPublic ? 'Visibilitas internal: semua user sistem bisa lihat' : 'Visibilitas internal: private (hanya pemilik & Director)'">
                <span class="w-1.5 h-1.5 mr-1.5 rounded-full"
                    :class="innerPublic ? 'bg-green-400 animate-pulse' : 'bg-red-400'"></span>
                <span x-text="innerPublic ? 'PUBLIC' : 'PRIVATE'"></span>
            </span>

            {{-- Link eksternal (terkunci bila internal private) --}}
            <button type="button"
                @click="toggleShare"
                :disabled="!innerPublic"
                :title="innerPublic
                    ? (isShared ? 'Matikan link eksternal' : 'Aktifkan link eksternal')
                    : 'VISIBILITAS INTERNAL MASIH PRIVATE - aktifkan dulu di form edit'"
                class="cursor-pointer px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border transition-colors inline-flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed"
                :class="isShared
                    ? 'bg-green-500/20 text-green-300 border-green-500/50 shadow-[0_0_8px_rgba(46,160,67,0.25)]'
                    : 'bg-surface-light text-secondary border-border'">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <span x-text="isShared ? 'SHARE_ON' : 'SHARE_OFF'"></span>
            </button>

            {{-- Mode iklan vs langsung --}}
            <div class="flex items-center gap-1.5">
                <button type="button"
                    @click="toggleAd"
                    :class="hasAd ? 'bg-yellow-900/40 text-yellow-400 border-yellow-500/40' : 'bg-surface-light text-secondary border-border'"
                    :disabled="!isShared"
                    class="cursor-pointer px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border transition-colors inline-flex items-center gap-1 disabled:opacity-40 disabled:cursor-not-allowed">
                    <span x-text="hasAd ? 'AD_MODE' : 'DIRECT'"></span>
                </button>

                <button type="button"
                    x-show="isShared && publicUrl"
                    @click="copyLink"
                    :title="copied ? 'LINK COPIED!' : 'Copy link publik'"
                    class="cursor-pointer text-green-400 hover:text-green-300 transition-colors"
                    :class="copied ? '!text-green-300' : ''">
                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <svg x-show="copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </div>
    @else
        {{-- PANEL: untuk halaman show --}}
        <div class="flex items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-green-400 uppercase tracking-widest">Internal Visibility (is_public)</span>
                <p class="text-[10px] text-secondary mt-1">
                    <span x-text="innerPublic
                        ? 'Semua user sistem (Agent) bisa melihat arsip ini. Diatur di form edit arsip.'
                        : 'PRIVATE: hanya pemilik & Director yang melihat. Diatur di form edit arsip.'"></span>
                </p>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border flex-shrink-0"
                :class="innerPublic ? 'bg-green-900/30 text-green-400 border-green-500/30' : 'bg-red-900/30 text-red-400 border-red-500/30'">
                <span x-text="innerPublic ? 'PUBLIC' : 'PRIVATE'"></span>
            </span>
        </div>

        {{-- Link eksternal --}}
        <div class="flex items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-green-400 uppercase tracking-widest">Public Link (External)</span>
                <p class="text-[10px] text-secondary mt-1">
                    <template x-if="innerPublic">
                        <span x-text="isShared
                            ? 'Link publik aktif & bisa dibagikan ke luar sistem.'
                            : 'Link publik non-aktif.'"></span>
                    </template>
                    <template x-if="!innerPublic">
                        <span class="text-red-400/80">Terkunci - aktifkan visibilitas internal PUBLIC dulu.</span>
                    </template>
                </p>
            </div>
            <button @click="toggleShare" :disabled="!innerPublic"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 border flex-shrink-0 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                :class="isShared ? 'bg-green-500/20 border-green-500' : 'bg-gray-800 border-gray-600'">
                <span class="inline-block h-3 w-3 transform rounded-full transition-all duration-300 ml-1"
                    :class="isShared ? 'translate-x-5 bg-green-400 shadow-[0_0_10px_rgba(46,203,67,1)]' : 'translate-x-0 bg-gray-500'"></span>
            </button>
        </div>

        <div class="flex items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-yellow-400 uppercase tracking-widest">Ad Mode (Earn)</span>
                <p class="text-[10px] text-secondary mt-1">
                    <span x-text="isShared
                        ? (hasAd
                            ? 'Pengunjung menunggu di halaman iklan (menghasilkan uang).'
                            : 'Link langsung menuju data tujuan (tanpa iklan).')
                        : 'Aktifkan Public Link dulu untuk memilih mode.'"></span>
                </p>
            </div>
            <button @click="toggleAd" :disabled="!isShared"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 border flex-shrink-0 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                :class="hasAd ? 'bg-yellow-500/20 border-yellow-500' : 'bg-gray-800 border-gray-600'">
                <span class="inline-block h-3 w-3 transform rounded-full transition-all duration-300 ml-1"
                    :class="hasAd ? 'translate-x-5 bg-yellow-400 shadow-[0_0_10px_rgba(234,179,8,1)]' : 'translate-x-0 bg-gray-500'"></span>
            </button>
        </div>

        {{-- Link publik + tombol copy --}}
        <div x-show="isShared && publicUrl" x-cloak class="bg-black/40 border border-green-500/30 rounded p-3">
            <div class="flex items-center gap-2">
                <span class="text-[10px] uppercase tracking-widest text-secondary flex-shrink-0">SHARE_LINK:</span>
                <code class="flex-grow text-[10px] sm:text-xs text-green-400 break-all" x-text="publicUrl"></code>
            </div>
            <div class="flex gap-2 mt-3">
                <a :href="publicUrl" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] uppercase tracking-wider font-bold bg-green-500/90 text-black rounded hover:bg-green-400 transition-colors">
                    OPEN
                </a>
                <button @click="copyLink"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] uppercase tracking-wider font-bold bg-surface-light text-green-400 border border-green-500/30 rounded hover:bg-surface transition-colors cursor-pointer">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span x-text="copied ? 'COPIED!' : 'COPY LINK'"></span>
                </button>
            </div>
        </div>
    @endif
</div>