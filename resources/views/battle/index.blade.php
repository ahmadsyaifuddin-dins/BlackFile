<x-app-layout title="Conflict Simulation">
    {{-- Init Alpine dengan passing Data PHP ke JS --}}
    <div x-data='battleSystem(@json($entities), "{{ route('battle.run') }}", "{{ csrf_token() }}")'
        class="min-h-screen text-gray-100 font-mono p-4 lg:p-8 relative overflow-hidden">

        {{-- Background --}}
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none"
            style="background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 40px 40px;"></div>

        {{-- Header --}}
        <div
            class="relative z-10 w-full max-w-7xl mx-auto mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-center md:text-left order-2 md:order-1">
                <h1
                    class="text-3xl md:text-5xl font-black tracking-widest text-primary uppercase drop-shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                    PROJECT: ARENA
                </h1>
                <p
                    class="text-secondary text-xs md:text-sm tracking-[0.3em] mt-2 border-b border-primary/30 pb-2 inline-block">
                    TACTICAL CONFLICT SIMULATION MODULE // V.3.3
                </p>
            </div>
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

        {{-- BATTLE GRID --}}
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto">

            {{-- LEFT: SUBJECT ALPHA --}}
            <div class="lg:col-span-4">
                <x-battle.card side="left" color="green" label="SUBJECT ALPHA" modelId="attackerId"
                    modelData="attacker" :entities="$entities" chartId="chartAlpha" />
            </div>

            {{-- CENTER: CONTROLS --}}
            <div class="lg:col-span-4 flex flex-col justify-center items-center gap-6 py-4">
                <div class="relative">
                    <div
                        class="text-6xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-b from-red-500 to-red-900 italic tracking-tighter animate-pulse">
                        VS</div>
                </div>

                <div class="w-full max-w-xs mb-4">
                    <label
                        class="text-[10px] text-gray-500 tracking-widest uppercase mb-1 block text-center">BATTLEFIELD
                        ENVIRONMENT</label>
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

                {{-- TOMBOL RANDOMIZE --}}
                <button @click="randomizeFighters()" type="button" :disabled="isSimulating"
                    class="cursor-pointer flex items-center gap-2 px-4 py-2 bg-gray-900 border border-gray-600 text-gray-400 hover:text-white hover:border-white transition-all duration-300 text-xs font-mono tracking-widest uppercase group">

                    {{-- Ikon Dadu / Shuffle (SVG) --}}
                    <svg class="w-4 h-4 group-hover:animate-spin" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>RANDOMIZE TARGETS</span>
                </button>

                <button @click="startSimulation()" :disabled="!attackerId || !defenderId || isSimulating"
                    :class="{
                        'opacity-50 cursor-not-allowed': !attackerId || !defenderId ||
                            isSimulating,
                        'hover:scale-105 hover:shadow-[0_0_20px_rgba(239,68,68,0.6)] cursor-pointer': attackerId &&
                            defenderId && !isSimulating
                    }"
                    class="group relative px-8 py-4 bg-black border-2 border-red-600 text-red-500 font-bold tracking-[0.2em] uppercase transition-all duration-300 w-full max-w-xs overflow-hidden">
                    <span class="relative z-10 group-hover:text-white transition-colors"
                        x-text="isSimulating ? 'CALCULATING...' : 'INITIATE SIMULATION'"></span>
                    <div
                        class="absolute inset-0 bg-red-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out">
                    </div>
                </button>

                {{-- WINNER REVEAL --}}
                <div x-show="winner" x-transition class="w-full mt-4">
                    <div :class="winnerIsAttacker ? 'border-l-4 border-primary bg-primary/10' :
                        'border-l-4 border-red-500 bg-red-500/10'"
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

            {{-- RIGHT: SUBJECT OMEGA --}}
            <div class="lg:col-span-4">
                <x-battle.card side="right" color="red" label="SUBJECT OMEGA" modelId="defenderId"
                    modelData="defender" :entities="$entities" chartId="chartOmega" />
            </div>
        </div>

        {{-- TERMINAL LOG & HISTORY --}}
        <div class="relative z-10 mt-8 max-w-7xl mx-auto grid grid-cols-1 gap-8">

            {{-- 1. TERMINAL --}}
            <div
                class="bg-black border border-gray-700 rounded-lg shadow-2xl relative h-64 md:h-80 overflow-hidden flex flex-col">

                {{-- SCANLINE LAYER --}}
                <div class="scanline pointer-events-none absolute inset-0 w-full h-full z-50"></div>

                {{-- A. HEADER SECTION (Diam/Tidak ikut scroll) --}}
                <div
                    class="px-4 pt-4 md:px-6 md:pt-6 pb-2 bg-black z-20 border-b border-gray-800 shrink-0 flex justify-between items-center">
                    <span class="text-gray-500 font-mono text-sm">> BATTLE_LOG.SYS</span>
                    <span x-text="isSimulating ? 'STATUS: RUNNING' : 'STATUS: IDLE'" class="font-mono text-sm"
                        :class="isSimulating ? 'text-green-400 animate-pulse' : 'text-gray-600'"></span>
                </div>

                {{-- B. LOGS CONTENT AREA (Hanya ini yang di-scroll) --}}
                <div class="flex-1 overflow-y-auto no-scrollbar p-4 md:p-6 pt-2 relative z-10 font-mono text-sm">

                    <div class="space-y-1" id="terminal-content" x-ref="terminalContainer">
                        {{-- Loop Log --}}
                        <template x-for="(log, index) in displayedLogs" :key="index">
                            <div class="flex gap-2 log-entry" :style="`animation-delay: ${index * 50}ms`">
                                <span class="text-gray-600 select-none shrink-0"
                                    x-text="'[' + String(index + 1).padStart(3, '0') + ']'"></span>

                                <span
                                    :class="{
                                        'text-primary font-bold': log.includes('WINNER') || log.includes(
                                            'successfully'),
                                        'text-red-500 font-bold': log.includes('CRITICAL') || log.includes(
                                            'succumbs') || log.includes('mutlak'),
                                        'text-yellow-500': log.includes('WARNING') || log.includes('taktis'),
                                        'text-blue-400': log.includes('Inisiasi') || log.includes('Kalkulasi'),
                                        'text-gray-300': !log.includes('WINNER') && !log.includes('CRITICAL')
                                    }"
                                    x-text="log"></span>
                            </div>
                        </template>

                        {{-- Loading State --}}
                        <div x-show="isSimulating" class="text-primary mt-2 terminal-cursor flex flex-col gap-1">
                            <span>> ESTABLISHING SECURE UPLINK...</span>
                            <span class="text-xs text-gray-500 animate-pulse">Warning: Neural Network latency
                                detected...</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 2. HISTORY TABLE --}}
            <div class="bg-surface/50 border border-gray-800 overflow-hidden rounded">
                <h3 class="text-primary text-xs font-bold tracking-widest p-4 uppercase border-b border-gray-800">
                    Recent
                    Conflict Archives</h3>
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-black/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-3">Time</th>
                            <th class="px-6 py-3">Alpha</th>
                            <th class="px-6 py-3">Omega</th>
                            <th class="px-6 py-3 text-center">Scenario</th>
                            <th class="px-6 py-3 text-right">Outcome</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($history as $log)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs whitespace-nowrap">
                                    {{ $log->created_at->diffForHumans() }}</td>
                                <td
                                    class="px-6 py-4 font-bold {{ $log->winner_id == $log->attacker_id ? 'text-primary' : 'text-gray-500' }}">
                                    {{ $log->attacker->name }}</td>
                                <td
                                    class="px-6 py-4 font-bold {{ $log->winner_id == $log->defender_id ? 'text-primary' : 'text-gray-500' }}">
                                    {{ $log->defender->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-1 text-[10px] rounded border {{ $log->scenario_type === 'HAZARD_TEST' ? 'border-yellow-600 text-yellow-500' : 'border-blue-900 text-blue-400' }}">{{ $log->scenario_type ?? 'SIMULATION' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-primary font-bold uppercase text-xs mb-1">WINNER:
                                        {{ $log->winner->name ?? 'UNKNOWN' }}</div>
                                    <div class="text-xs text-gray-500">PROB: <span
                                            class="text-white font-mono">{{ $log->win_probability }}%</span></div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-600 italic">[ NO RECORDS ]
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
