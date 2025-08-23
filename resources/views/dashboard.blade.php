<x-app-layout>
    <x-slot:title>
        SYSTEM COMMAND DASHBOARD
    </x-slot:title>

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

    {{-- Direct Actions --}}
    <div class="mb-6 font-mono">
        <h2 class="text-lg font-bold text-primary mb-3">> DIRECT ACTIONS</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('entities.create') }}" class="text-center bg-surface border-2 border-border-color p-4 hover:border-primary hover:text-primary transition-colors">
                <p class="font-bold text-primary text-lg">[+]</p>
                <p class="text-xs text-secondary mt-1">REGISTER ENTITY</p>
            </a>
            <a href="{{ route('prototypes.create') }}" class="text-center bg-surface border-2 border-border-color p-4 hover:border-primary hover:text-primary transition-colors">
                <p class="font-bold text-primary text-lg">[+]</p>
                <p class="text-xs text-secondary mt-1">INITIATE PROJECT</p>
            </a>
            <a href="{{ route('central-tree') }}" class="text-center bg-surface border-2 border-border-color p-4 hover:border-primary hover:text-primary transition-colors">
                <p class="font-bold text-primary text-lg">[O]</p>
                <p class="text-xs text-secondary mt-1">VIEW CENTRAL TREE</p>
            </a>
            @if(strtolower(Auth::user()->role->name) === 'director' || strtolower(Auth::user()->role->name) === 'technician')
            <a href="{{ route('register') }}" class="text-center bg-surface border-2 border-border-color p-4 hover:border-primary hover:text-primary transition-colors">
                <p class="font-bold text-primary text-lg">[+]</p>
                <p class="text-xs text-secondary mt-1">REGISTER AGENT</p>
            </a>
            @endif
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- == PANEL BARU: GLOBAL MONITORING FEED == --}}
    {{-- ================================================================ --}}
    {{-- GLOBAL MONITORING FEED --}}
    <div class="mb-6 font-mono">
        <h2 class="text-lg font-bold text-primary mb-3">> GLOBAL MONITORING FEED</h2>
        <div class="iframeReal grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- World Population --}}
            {{-- [PERBAIKAN] Menambahkan class 'iframe-wrapper' ke div pembungkus --}}
            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                {{-- [PERBAIKAN] Menghapus class 'iframe-green-tint' dari iframe --}}
                <iframe title='World population' src='https://www.theworldcounts.com/embeds/counters/8?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>
            {{-- Food Supply --}}
            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Earth running out of food' src='https://www.theworldcounts.com/embeds/counters/112?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>
            {{-- Freshwater Supply --}}
            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Earth running out of freshwater' src='https://www.theworldcounts.com/embeds/counters/113?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>
        </div>
    </div>

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 font-mono">
        
        {{-- Kolom Kiri (Metrics, Alerts, & Agent Status) --}}
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

            {{-- Agent Network Status --}}
            <div class="border border-border-color bg-surface p-4">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> AGENT NETWORK STATUS</h2>
                <div class="space-y-3 text-sm">
                    @forelse($activeAgents as $agent)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if($agent->last_active_at->diffInMinutes(now()) < 5)
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-3 animate-pulse"></span>
                                @else
                                    <span class="w-2 h-2 bg-gray-600 rounded-full mr-3"></span>
                                @endif
                                <span class="text-white">{{ $agent->codename }}</span>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $agent->last_active_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <p class="text-secondary">> No other agent activity detected.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan (Project Status & Recent Activity) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Project Status Overview --}}
            <div class="border border-border-color bg-surface p-4">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> PROJECT STATUS OVERVIEW</h2>
                <div class="space-y-4 text-sm">
                    @forelse($projectStatuses as $status)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-white">{{ $status->status }}</span>
                                <span class="text-secondary">{{ $status->total }} / {{ $totalPrototypes }}</span>
                            </div>
                            <div class="w-full bg-base border border-border-color h-4">
                                @php
                                    $percentage = $totalPrototypes > 0 ? ($status->total / $totalPrototypes) * 100 : 0;
                                @endphp
                                <div class="bg-green-800 h-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-secondary">> No project data available to display.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Activity Log --}}
            <div class="border border-border-color bg-surface p-4">
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
    </div>
</x-app-layout>
