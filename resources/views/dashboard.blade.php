<x-app-layout>
    <x-slot:title>
        SYSTEM COMMAND DASHBOARD
    </x-slot>

    {{-- Header & System Time --}}
    <div x-data="{ time: new Date().toLocaleTimeString('en-GB') }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-GB') }, 1000)"
        class="mb-6 font-mono border-b-2 border-border-color pb-4">
        <h1 class="text-2xl font-bold text-primary tracking-wider">
            > Welcome, <span class="text-white">{{ Auth::user()->codename }}</span> <span class="text-primary animate-pulse">_</span>
        </h1>
        <p class="text-sm text-secondary">
            SYSTEM TIME: <span x-text="time" class="text-white"></span> // STATUS: <span class="text-green-400">NORMAL</span>
        </p>
    </div>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 font-mono">
        
        {{-- Kolom Kiri (Metrics & Alerts) --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Key Metrics --}}
            <div class="border border-border-color bg-surface p-4">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> KEY METRICS</h2>
                <div class="space-y-2 text-sm">
                    <p class="flex justify-between"><span>> AGENT ROSTER:</span> <span class="text-white font-bold">{{ $totalAgents }}</span></p>
                    <p class="flex justify-between"><span>> ENTITY DATABASE:</span> <span class="text-white font-bold">{{ $totalEntities }}</span></p>
                    <p class="flex justify-between"><span>> PROJECT PIPELINE:</span> <span class="text-white font-bold">{{ $totalPrototypes }}</span></p>
                </div>
            </div>

            {{-- System Alert --}}
            <div class="border-2 border-yellow-500/50 bg-yellow-900/20 p-4 animate-pulse">
                <h2 class="text-lg font-bold text-yellow-400 border-b border-yellow-500/30 pb-2 mb-3">> SYSTEM ALERT</h2>
                @if($systemAlert)
                    <p class="text-yellow-300 text-sm">High-threat entity detected:</p>
                    <a href="{{ route('entities.show', $systemAlert) }}" class="block mt-1 text-white font-bold text-md hover:underline">
                        {{ $systemAlert->codename ?? $systemAlert->name }}
                    </a>
                    <p class="text-xs text-yellow-400/70">Classification: {{ $systemAlert->rank }}</p>
                @else
                    <p class="text-yellow-300 text-sm">> All systems clear. No immediate high-threat entities detected.</p>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan (Recent Activity) --}}
        <div class="lg:col-span-2 border border-border-color bg-surface p-4">
            <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> RECENT ACTIVITY LOG</h2>
            <div class="space-y-3 text-sm">
                @forelse($recentActivities as $activity)
                    <div class="flex justify-between items-baseline">
                        <p class="truncate">
                            <span class="text-secondary mr-2"> > Dossier updated:</span>
                            <a href="{{ route('entities.show', $activity) }}" class="text-white hover:text-primary transition-colors">
                                {{ $activity->codename ?? $activity->name }}
                            </a>
                        </p>
                        <span class="text-xs text-gray-500 flex-shrink-0 ml-4">
                            {{ $activity->updated_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <p class="text-secondary">> No recent activity in the entity database.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
