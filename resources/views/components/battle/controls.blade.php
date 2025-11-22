<div class="lg:col-span-4 flex flex-col justify-center items-center gap-6 py-4">
    {{-- VS Label --}}
    <div class="relative">
        <div
            class="text-6xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-b from-red-500 to-red-900 italic tracking-tighter animate-pulse">
            VS
        </div>
    </div>

    {{-- Arena Selector --}}
    <div class="w-full max-w-xs mb-4">
        <label class="text-[10px] text-gray-500 tracking-widest uppercase mb-1 block text-center">
            BATTLEFIELD ENVIRONMENT
        </label>
        <select x-model="arena"
            class="w-full bg-black border border-gray-700 text-gray-300 text-xs uppercase p-2 text-center focus:border-white focus:ring-0 tracking-wider">
            <option value="NEUTRAL">NEUTRAL GROUND (DEFAULT)</option>
            <option value="OCEANIC">OCEANIC DEPTHS (LAUT)</option>
            <option value="INFERNAL">INFERNAL REALM (NERAKA)</option>
            <option value="SANCTUARY">HOLY SANCTUARY (SUCI)</option>
            <option value="URBAN">URBAN COMPLEX (KOTA)</option>
            <option value="VOID">DEEP SPACE (ANGKASA)</option>
        </select>
    </div>

    {{-- Randomize Button --}}
    <button @click="randomizeFighters()" type="button" :disabled="isSimulating"
        class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-900 border border-gray-600 text-gray-400 hover:text-white hover:border-white transition-all duration-300 text-xs font-mono tracking-widest uppercase group">
        <svg class="w-4 h-4 group-hover:animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span>RANDOMIZE TARGETS</span>
    </button>

    {{-- Start Button --}}
    <button @click="startSimulation()" :disabled="!attackerId || !defenderId || isSimulating"
        :class="{
            'opacity-50 cursor-not-allowed': !attackerId || !defenderId || isSimulating,
            'hover:scale-105 hover:shadow-[0_0_20px_rgba(239,68,68,0.6)] cursor-pointer': attackerId && defenderId && !
                isSimulating
        }"
        class="group relative px-8 py-4 bg-black border-2 border-red-600 text-red-500 font-bold tracking-[0.2em] uppercase transition-all duration-300 w-full max-w-xs overflow-hidden">
        <span class="relative z-10 group-hover:text-white transition-colors"
            x-text="isSimulating ? 'CALCULATING...' : 'INITIATE SIMULATION'"></span>
        <div
            class="absolute inset-0 bg-red-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out">
        </div>
    </button>

    {{-- Winner Reveal --}}
    <div x-show="winner" x-transition class="w-full mt-4">
        <div :class="winnerIsAttacker ? 'border-l-4 border-primary bg-primary/10' : 'border-l-4 border-red-500 bg-red-500/10'"
            class="p-4 border border-gray-700 backdrop-blur-md">
            <h2 class="text-2xl font-black text-white uppercase mb-1" x-text="winner?.name"></h2>
            <p class="text-sm font-mono" :class="winnerIsAttacker ? 'text-primary' : 'text-red-400'">
                PROBABILITY: <span x-text="winProbability"></span>%
            </p>
            <p class="text-xs text-gray-300 mt-2 border-t border-gray-700 pt-2 italic">"<span
                    x-text="winReason"></span>"</p>
        </div>
    </div>
</div>
