<div class="relative z-10 w-full max-w-7xl mx-auto mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
    {{-- Title Section --}}
    <div class="text-center md:text-left order-2 md:order-1">
        <h1
            class="text-3xl md:text-5xl font-black tracking-widest text-primary uppercase drop-shadow-[0_0_10px_rgba(16,185,129,0.5)]">
            PROJECT: ARENA
        </h1>
        <p class="text-secondary text-xs md:text-sm tracking-[0.3em] mt-2 border-b border-primary/30 pb-2 inline-block">
            TACTICAL CONFLICT SIMULATION MODULE // V.3.3
        </p>
    </div>

    {{-- Audio Control --}}
    <div class="order-1 md:order-2">
        <button @click="toggleAudio()"
            class="flex items-center gap-2 px-4 py-2 bg-black/80 border border-gray-700 rounded-full text-[10px] font-bold tracking-wider hover:border-primary transition-all shadow-lg backdrop-blur-sm cursor-pointer"
            :class="isMuted ? 'text-gray-500 border-gray-800' : 'text-primary border-primary/50'">
            <template x-if="!isMuted">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span>AUDIO: ON</span>
                </div>
            </template>
            <template x-if="isMuted">
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full bg-gray-600"></div>
                    <span>AUDIO: OFF</span>
                </div>
            </template>
        </button>
    </div>
</div>
