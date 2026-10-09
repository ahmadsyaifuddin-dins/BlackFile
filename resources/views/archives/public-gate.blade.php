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
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
        .loader { animation: spinner 1s linear infinite; }
    </style>
</head>
<body class="bg-base text-text-default min-h-screen scanlines" data-theme="default">

    <div class="max-w-2xl mx-auto px-4 py-16 font-mono text-center" x-data="{
        seconds: {{ $gateSeconds }},
        init() {
            this.$refs.bar.style.animationDuration = this.seconds + 's';
            const timer = setInterval(() => {
                this.seconds--;
                if (this.seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = @js($dataUrl);
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
                &gt; Redirecting to target data in <span class="text-green-400 text-base font-bold" x-text="seconds"></span> seconds...
                <span class="cursor-blink">▊</span>
            </p>
        </div>

        {{-- Progress Bar --}}
        <div class="w-full h-2 bg-black/50 border border-green-500/30 rounded overflow-hidden mb-10">
            <div x-ref="bar" class="progress-animated h-full bg-green-500 shadow-[0_0_10px_#2ea043]"></div>
        </div>

        {{-- AD SPACE --}}
        <div class="relative border border-dashed border-green-500/40 rounded-lg p-6 mb-10 bg-surface/40 min-h-[160px]">
            <span class="absolute top-2 left-2 text-[9px] uppercase tracking-[0.25em] text-secondary/60">// AD_SPACE</span>
            @include('archives.partials._adsense')
        </div>

        {{-- Ad counter --}}
        <p class="text-xs text-secondary/70 mb-6">
            &gt; SPONSORED_ACCESS // By continuing, you support free access.
        </p>

        <p class="text-[10px] text-secondary/40 uppercase tracking-widest">
            Powered by <span class="text-green-500/60">BLACKFILE</span> Systems
        </p>
    </div>

</body>
</html>