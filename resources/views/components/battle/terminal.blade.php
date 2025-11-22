<div class="bg-black border border-gray-700 rounded-lg shadow-2xl relative h-64 md:h-80 overflow-hidden flex flex-col">
    {{-- SCANLINE LAYER --}}
    <div class="scanline pointer-events-none absolute inset-0 w-full h-full z-50"></div>

    {{-- A. HEADER SECTION --}}
    <div
        class="px-4 pt-4 md:px-6 md:pt-6 pb-2 bg-black z-20 border-b border-gray-800 shrink-0 flex justify-between items-center">
        <span class="text-gray-500 font-mono text-sm">> BATTLE_LOG.SYS</span>
        <span x-text="isSimulating ? 'STATUS: RUNNING' : 'STATUS: IDLE'" class="font-mono text-sm"
            :class="isSimulating ? 'text-green-400 animate-pulse' : 'text-gray-600'"></span>
    </div>

    {{-- B. LOGS CONTENT AREA --}}
    <div class="flex-1 overflow-y-auto no-scrollbar p-4 md:p-6 pt-2 relative z-10 font-mono text-sm">
        <div class="space-y-1" id="terminal-content" x-ref="terminalContainer">
            <template x-for="(log, index) in displayedLogs" :key="index">
                <div class="flex gap-2 log-entry" :style="`animation-delay: ${index * 50}ms`">
                    <span class="text-gray-600 select-none shrink-0"
                        x-text="'[' + String(index + 1).padStart(3, '0') + ']'"></span>
                    <span
                        :class="{
                            'text-primary font-bold': log.includes('WINNER') || log.includes('successfully'),
                            'text-red-500 font-bold': log.includes('CRITICAL') || log.includes('succumbs') || log
                                .includes('mutlak'),
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
                <span class="text-xs text-gray-500 animate-pulse">Warning: Neural Network latency detected...</span>
            </div>
        </div>
    </div>
</div>
