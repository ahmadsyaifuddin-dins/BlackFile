<x-app-layout title="Battle Archive #{{ $history->id }}">
    <div class="min-h-screen text-gray-100 font-mono p-4 lg:p-8 relative overflow-hidden">

        {{-- Background Grid --}}
        <x-battle.background />

        {{-- 1. HEADER: Navigation & Meta Data --}}
        <div class="relative z-10 max-w-5xl mx-auto mb-8 flex justify-between items-end border-b border-gray-800 pb-4">
            <div>
                <a href="{{ route('battle.index') }}"
                    class="flex items-center gap-2 text-xs text-gray-500 hover:text-primary transition-colors mb-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">
                        << /span>
                            <span>RETURN TO ARCHIVES</span>
                </a>
                <h1 class="text-2xl md:text-3xl font-black tracking-widest text-white uppercase">
                    ARCHIVE RECORD <span class="text-primary">#{{ str_pad($history->id, 4, '0', STR_PAD_LEFT) }}</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    TIMESTAMP: {{ $history->created_at->format('Y-m-d H:i:s') }} //
                    TYPE: <span
                        class="{{ $history->scenario_type === 'HAZARD_TEST' ? 'text-yellow-500' : 'text-blue-400' }}">{{ $history->scenario_type }}</span>
                </p>
            </div>

            {{-- Status Stamp --}}
            <div class="hidden md:block">
                <div
                    class="border-2 {{ $history->winner_id ? 'border-primary text-primary' : 'border-gray-500 text-gray-500' }} px-4 py-2 font-black text-xl tracking-[0.2em] uppercase rotate-[-5deg] opacity-80 select-none">
                    {{ $history->winner_id ? 'MISSION COMPLETE' : 'INCONCLUSIVE' }}
                </div>
            </div>
        </div>

        {{-- 2. COMBATANTS SUMMARY --}}
        <div class="relative z-10 max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

            {{-- Attacker --}}
            <div class="bg-black/40 border border-gray-800 p-6 rounded relative group overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-green-500"></div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-gray-500">Subject Alpha
                            (Attacker)</span>
                        <h2 class="text-xl font-bold text-green-400">{{ $history->attacker->name }}</h2>
                        <span
                            class="text-xs bg-gray-900 text-gray-400 px-2 py-1 rounded mt-1 inline-block border border-gray-700">
                            TIER {{ $history->attacker->tier }}
                        </span>
                    </div>
                    @if ($history->winner_id == $history->attacker_id)
                        <span
                            class="text-primary font-bold text-4xl opacity-20 group-hover:opacity-100 transition-opacity">WIN</span>
                    @endif
                </div>
                {{-- Stat Bar Visual Only --}}
                <div class="w-full h-1 bg-gray-800 mt-2">
                    <div class="h-full bg-green-500" style="width: {{ $history->attacker->tier * 10 }}%"></div>
                </div>
            </div>

            {{-- Defender --}}
            <div class="bg-black/40 border border-gray-800 p-6 rounded relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-1 h-full bg-red-500"></div>
                <div class="flex justify-between items-start mb-4 text-right md:flex-row-reverse">
                    <div class="text-right">
                        <span class="text-[10px] uppercase tracking-widest text-gray-500">Subject Omega
                            (Defender)</span>
                        <h2 class="text-xl font-bold text-red-400">{{ $history->defender->name }}</h2>
                        <span
                            class="text-xs bg-gray-900 text-gray-400 px-2 py-1 rounded mt-1 inline-block border border-gray-700">
                            TIER {{ $history->defender->tier }}
                        </span>
                    </div>
                    @if ($history->winner_id == $history->defender_id)
                        <span
                            class="text-primary font-bold text-4xl opacity-20 group-hover:opacity-100 transition-opacity">WIN</span>
                    @endif
                </div>
                {{-- Stat Bar Visual Only --}}
                <div class="w-full h-1 bg-gray-800 mt-2 flex justify-end">
                    <div class="h-full bg-red-500" style="width: {{ $history->defender->tier * 10 }}%"></div>
                </div>
            </div>

        </div>

        {{-- 3. BATTLE LOG TERMINAL (Static View) --}}
        <div class="relative z-10 max-w-5xl mx-auto">
            <div class="bg-black border border-gray-700 rounded-lg shadow-2xl relative overflow-hidden flex flex-col">

                {{-- Header --}}
                <div class="px-4 py-3 bg-gray-900/80 border-b border-gray-800 flex justify-between items-center">
                    <span class="text-gray-500 font-mono text-sm">> DECRYPTED_LOG.TXT</span>
                    <span class="text-xs text-gray-600">READ_ONLY_MODE</span>
                </div>

                {{-- Content --}}
                <div class="p-6 font-mono text-sm space-y-2 h-[400px] overflow-y-auto custom-scrollbar">
                    @if (is_array($history->logs) || is_object($history->logs))
                        @foreach ($history->logs as $index => $log)
                            <div
                                class="flex gap-4 border-b border-gray-900/50 pb-1 mb-1 hover:bg-white/5 transition-colors">
                                <span
                                    class="text-gray-700 select-none shrink-0 w-8 text-right">{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</span>
                                <span
                                    class="{{ Str::contains($log, ['WINNER', 'successfully', 'Dominasi', 'Kemenangan'])
                                        ? 'text-primary font-bold'
                                        : (Str::contains($log, ['CRITICAL', 'succumbs', 'mutlak', 'FATALITY', 'hancur'])
                                            ? 'text-red-500 font-bold'
                                            : (Str::contains($log, ['WARNING', 'Alert', 'DETECT'])
                                                ? 'text-yellow-500'
                                                : 'text-gray-300')) }}">
                                    {!! $log !!}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-red-500">ERROR: LOG DATA CORRUPTED OR INVALID FORMAT.</div>
                    @endif

                    {{-- Outcome Footer --}}
                    <div class="mt-8 pt-4 border-t border-gray-800">
                        <div class="flex items-center gap-2 text-primary">
                            <span>>> FINAL OUTCOME:</span>
                            <span class="font-bold">
                                {{ $history->winner ? $history->winner->name . ' VICTORIOUS' : 'DRAW / MUTUAL DESTRUCTION' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-500 text-xs mt-1">
                            <span>>> CALCULATED PROBABILITY:</span>
                            <span class="font-mono text-white">{{ $history->win_probability }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Scanline Effect (Static) --}}
                <div
                    class="pointer-events-none absolute inset-0 w-full h-full bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] z-50 bg-[length:100%_2px,3px_100%]">
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
