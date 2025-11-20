<x-app-layout title="NARRATIVE RADAR // BLACKFILE">
    {{-- Container Utama: Fixed Height minus Navbar --}}
    <div class="flex flex-col h-[calc(100vh-65px)] bg-base overflow-hidden">

        <div
            class="px-8 py-6 border-b border-amber-900/30 bg-surface/50 backdrop-blur-sm flex justify-between items-center z-20">
            <div>
                <h1 class="text-3xl font-bold tracking-wider text-amber-500 uppercase">
                    <i class="fa-solid fa-comments mr-3"></i>NARRATIVE RADAR
                </h1>
                <p class="text-secondary text-sm mt-1 font-mono">
                    // COMMENT ANALYTICS & SENTIMENT ENGINE
                </p>
            </div>

            <div class="text-right hidden md:block">
                <div class="text-xs text-secondary">MODULE STATUS</div>
                <div class="text-amber-500 font-bold animate-pulse">DEV // IN_PROGRESS</div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-8 relative no-scrollbar">

            <div class="absolute inset-0 pointer-events-none opacity-5"
                style="background-image: radial-gradient(#f59e0b 1px, transparent 1px); background-size: 30px 30px;">
            </div>

            <div class="max-w-4xl mx-auto relative z-10">

                <div
                    class="border border-amber-500/30 bg-surface shadow-[0_0_50px_rgba(245,158,11,0.15)] rounded-sm overflow-hidden">

                    <div class="bg-black/40 border-b border-amber-500/20 px-4 py-2 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/80"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-500/80"></div>
                            <span class="ml-4 text-xs text-amber-600 font-mono hidden sm:inline-block">
                                root@blackfile:~/modules/narrative-radar
                            </span>
                        </div>
                        <div class="text-[10px] text-amber-900/50 font-mono font-bold">v0.1.0-ALPHA</div>
                    </div>

                    <div class="p-8 font-mono text-sm space-y-8">

                        <div class="space-y-2 font-mono text-xs sm:text-sm">
                            <div class="flex gap-3">
                                <span class="text-gray-600">[</span><span
                                    class="text-green-500 font-bold">OK</span><span class="text-gray-600">]</span>
                                <span class="text-gray-400">Initializing core module...</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-gray-600">[</span><span
                                    class="text-green-500 font-bold">OK</span><span class="text-gray-600">]</span>
                                <span class="text-gray-400">Loading semantic dependencies...</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-gray-600">[</span><span
                                    class="text-amber-500 font-bold animate-pulse">WARN</span><span
                                    class="text-gray-600">]</span>
                                <span class="text-amber-500">Connecting to sentiment analysis engine...</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-gray-600">[</span><span class="text-red-500 font-bold">ERR</span><span
                                    class="text-gray-600">]</span>
                                <span class="text-red-400">Module not ready for deployment. Code: 503</span>
                            </div>
                        </div>

                        <div class="h-px bg-amber-500/20 w-full"></div>

                        <div class="bg-amber-950/20 border-l-4 border-amber-500 p-6">
                            <div class="flex items-start gap-5">
                                <i class="fa-solid fa-triangle-exclamation text-3xl text-amber-500 mt-1 shrink-0"></i>
                                <div>
                                    <h3 class="text-lg font-bold text-amber-500 mb-3 tracking-widest uppercase">
                                        UNDER CONSTRUCTION
                                    </h3>
                                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                                        Modul <strong class="text-amber-400">Narrative Radar</strong> sedang dalam tahap
                                        pengembangan intensif.
                                        Sistem ini dirancang untuk menganalisis ribuan komentar secara real-time guna
                                        mendeteksi pola provokasi, bot farm, dan sentimen publik.
                                    </p>

                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-8 text-xs text-gray-500 font-bold">
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-500">▸</span> Sentiment Analysis (AI)
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-500">▸</span> Bot Detection Algorithm
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-500">▸</span> Keyword Pattern Matching
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-amber-500">▸</span> Mass Log Processing
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2">
                            <div class="flex justify-between text-[10px] text-secondary uppercase tracking-wider">
                                <span>Development Progress</span>
                                <span class="text-amber-500 font-bold">35%</span>
                            </div>
                            <div class="h-2 bg-gray-900 rounded-full overflow-hidden border border-amber-900/30">
                                <div
                                    class="h-full bg-amber-500 w-[35%] shadow-[0_0_15px_rgba(245,158,11,0.6)] relative overflow-hidden">
                                    <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"></div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-amber-500/20 w-full"></div>

                        <div class="flex items-center justify-between flex-wrap gap-4 pt-2">
                            <div
                                class="flex items-center space-x-2 text-sm font-mono bg-black/30 px-3 py-2 rounded border border-amber-900/30">
                                <span class="text-amber-500">$</span>
                                <span class="text-gray-400">system_status --check</span>
                                <span class="w-2 h-4 bg-amber-500 animate-pulse"></span>
                            </div>

                            <a href="{{ route('tools.index') }}"
                                class="group flex items-center gap-3 px-6 py-3 bg-transparent border border-amber-500/50 text-amber-500 hover:bg-amber-500 hover:text-black transition-all duration-300 text-xs font-bold uppercase tracking-widest rounded hover:shadow-[0_0_20px_rgba(245,158,11,0.4)]">
                                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                                <span>Return to Arsenal</span>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="mt-8 text-center opacity-60 hover:opacity-100 transition-opacity">
                    <p class="text-[10px] text-secondary font-mono">
                        Estimated Completion: <span class="text-amber-500">Q2 2025</span> <span
                            class="mx-2 text-gray-700">|</span>
                        Priority: <span class="text-amber-500">HIGH</span> <span class="mx-2 text-gray-700">|</span>
                        Clearance: <span class="text-amber-500">LEVEL 3</span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- CSS Helper untuk Hide Scrollbar tapi tetap scrollable --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }
    </style>
</x-app-layout>
