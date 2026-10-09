<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCESSING... // BLACKFILE</title>
    @vite(['resources/css/app.css'])
    @include('archives.partials._adsense-loader')
    <style>
        [x-cloak] { display: none !important; }
        .scanlines::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0px,
                transparent 2px,
                rgba(46, 160, 67, 0.06) 3px,
                rgba(46, 160, 67, 0.06) 4px
            );
            z-index: 50;
        }
        @keyframes blinkCursor {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        .cursor-blink { animation: blinkCursor 1s step-end infinite; }
        @keyframes progressBar {
            from { width: 0%; }
            to { width: 100%; }
        }
        .progress-animated { animation: progressBar linear forwards; }
        @keyframes glowPulse {
            0%, 100% { text-shadow: 0 0 12px rgba(46,160,67,.55); }
            50% { text-shadow: 0 0 26px rgba(46,160,67,.9); }
        }
        .title-glow { animation: glowPulse 2.4s ease-in-out infinite; }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .btn-locked {
            background: linear-gradient(90deg, rgba(0,0,0,.4) 25%, rgba(46,160,67,.15) 50%, rgba(0,0,0,.4) 75%);
            background-size: 200% 100%;
            animation: shimmer 2.4s linear infinite;
        }
    </style>
</head>
<body class="bg-base text-text-default min-h-screen scanlines" data-theme="default">

    <div class="max-w-2xl mx-auto px-4 py-16 font-mono text-center" x-data="{
        seconds: {{ $gateSeconds }},
        unlocked: false,
        init() {
            this.$refs.bar.style.animationDuration = this.seconds + 's';
            const timer = setInterval(() => {
                this.seconds--;
                if (this.seconds <= 0) {
                    this.unlocked = true;
                    clearInterval(timer);
                }
            }, 1000);
        }
    }">

        {{-- Header --}}
        <div class="mb-10">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_#2ea043]"></div>
                <span class="text-xs tracking-[0.3em] text-secondary">BLACKFILE // SECURE_LINK</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-primary title-glow break-words">
                {{ $archive->name }}
            </h1>
            <p class="text-[11px] text-secondary/70 mt-2">
                <template x-if="!unlocked">
                    <span>&gt; IKLAN DIMUAT // TOMBOL TERBUKA DALAM
                        <span class="text-green-400 text-base font-bold" x-text="seconds"></span>
                        DETIK <span class="cursor-blink">▊</span></span>
                </template>
                <template x-if="unlocked">
                    <span class="text-green-400">&gt; AKSES TERBUKA // KERJAKAN</span>
                </template>
            </p>
        </div>

        {{-- Progress Bar --}}
        <div class="w-full h-2 bg-black/50 border border-green-500/30 rounded overflow-hidden mb-10">
            <div x-ref="bar" class="progress-animated h-full bg-green-500 shadow-[0_0_10px_#2ea043]"></div>
        </div>

        {{-- AD SPACE --}}
        <div class="relative border border-dashed border-green-500/40 rounded-lg p-6 mb-10 bg-surface/40 min-h-[160px]">
            <span class="absolute top-2 left-2 text-[9px] uppercase tracking-[0.25em] text-secondary/60">// AD_SPACE</span>
            @include('archives.partials._adsense', ['label' => 'gerbang'])
            @include('archives.partials._adsterra')
        </div>

        {{-- Sponsor note --}}
        <p class="text-xs text-secondary/70 mb-6">
            &gt; SPONSORED_ACCESS // Dengan melanjutkan, Anda mendukung akses gratis ke konten ini.
        </p>

        {{-- Manual continue (unlock setelah timer habis) --}}
        <button type="button"
            @click="window.location.href = @js($dataUrl)"
            :disabled="!unlocked"
            class="cursor-pointer w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded font-bold uppercase tracking-widest text-sm transition-all border"
            :class="unlocked
                ? 'bg-green-500/90 text-black border-green-400 shadow-[0_0_20px_rgba(46,160,67,0.45)] hover:bg-green-400'
                : 'btn-locked text-secondary/60 border-border opacity-80 cursor-not-allowed'">

            <svg x-show="!unlocked" x-cloak class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <svg x-show="unlocked" x-cloak class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7v2a4 4 0 01-4 4H9m0 0a4 4 0 01-4-4V7a4 4 0 014-4h2a4 4 0 014 4m0 0h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8a2 2 0 012-2" />
            </svg>

            <span x-text="unlocked ? 'SELESAI, MENUJU TAUTAN \u2192' : 'MENUNGGU IKLAN...'"></span>
        </button>

        <p class="text-[10px] text-secondary/40 uppercase tracking-widest mt-8">
            Powered by <span class="text-green-500/60">BLACKFILE</span> Systems
        </p>
    </div>

</body>
</html>