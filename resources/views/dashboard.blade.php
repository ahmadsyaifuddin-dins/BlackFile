<x-app-layout>
    <x-slot:title>
        SYSTEM COMMAND DASHBOARD
    </x-slot:title>

    {{-- Header & System Time --}}
    <div x-data="{ time: new Date().toLocaleTimeString('en-GB') }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('en-GB') }, 1000)"
        class="mb-6 font-mono border-b-2 border-border-color pb-4">
        <h1 class="text-xl sm:text-2xl font-bold text-primary tracking-wider">
            <span class="text-glow">> Welcome, </span><span class="text-white">{{ Auth::user()->codename }}</span> <span class="text-primary animate-pulse">_</span>
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
            <a href="{{ route('register.agent') }}" class="text-center bg-surface border-2 border-border-color p-4 hover:border-primary hover:text-primary transition-colors">
                <p class="font-bold text-primary text-lg">[+]</p>
                <p class="text-xs text-secondary mt-1">REGISTER AGENT</p>
            </a>
            @endif
        </div>
    </div>

    {{-- GLOBAL MONITORING FEED --}}
    <div class="mb-6 font-mono">
        <h2 class="text-lg font-bold text-primary mb-3">> GLOBAL MONITORING FEED</h2>
        <div class="iframeReal grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- World Population --}}
            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='World population' src='https://www.theworldcounts.com/embeds/counters/8?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='World average temperature (°C)' src='https://www.theworldcounts.com/embeds/counters/21?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='Rise in sea levels (cm)' src='https://www.theworldcounts.com/embeds/counters/68?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            {{-- Food Supply --}}
            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Earth running out of food' src='https://www.theworldcounts.com/embeds/counters/112?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>
            {{-- Freshwater Supply --}}
            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Earth running out of freshwater' src='https://www.theworldcounts.com/embeds/counters/113?background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='Population of Asia' src='https://www.theworldcounts.com/embeds/counters/129?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='Population of Indonesia' src='https://www.theworldcounts.com/embeds/counters/138?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='People who died from hunger' src='https://www.theworldcounts.com/embeds/counters/2?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Number of obese people' src='https://www.theworldcounts.com/embeds/counters/51?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Tonnes of food lost or wasted' src='https://www.theworldcounts.com/embeds/counters/101?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Tonnes of paper produced' src='https://www.theworldcounts.com/embeds/counters/69?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Time left till the end of rainforests' src='https://www.theworldcounts.com/embeds/counters/114?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='110' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Tonnes of freshwater used' src='https://www.theworldcounts.com/embeds/counters/9?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Liters of water you have used' src='https://www.theworldcounts.com/embeds/counters/11?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='Deaths from dirty water and related diseases' src='https://www.theworldcounts.com/embeds/counters/12?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='110' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
                <iframe title='US dollars spent on consumer electronics' src='https://www.theworldcounts.com/embeds/counters/84?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='110' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='Cars produced' src='https://www.theworldcounts.com/embeds/counters/73?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='100' width='100%'></iframe>
            </div>

            <div class="iframe-wrapper border border-border-color p-2 bg-base">
            <iframe title='Tonnes of resources mined from Earth' src='https://www.theworldcounts.com/embeds/counters/16?background_color=background_color=0d1117&color=ffffff&font_family=%22Roboto+Mono%22%2C+monospace' style='border: none' height='110' width='100%'></iframe>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 font-mono">
        
        {{-- Box 1: Key Metrics (Kiri Atas) --}}
        <div class="lg:col-span-1 border border-border-color bg-surface p-4">
            <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> KEY METRICS</h2>
            <div class="space-y-2 text-sm">
                <p class="flex justify-between"><span>> AGENT ROSTER:</span> <span class="text-white font-bold">{{ $totalAgents }}</span></p>
                <p class="flex justify-between"><span>> ENTITY DATABASE:</span> <span class="text-white font-bold">{{ $totalEntities }}</span></p>
                <p class="flex justify-between border-b border-border-color pb-2"><span>> PROJECT PIPELINE:</span> <span class="text-white font-bold">{{ $totalPrototypes }}</span></p>
                <p class="flex justify-between"><span>> PUBLIC ARCHIVES:</span> <span class="text-white font-bold">{{ $totalPublicArchives }}</span></p>
                <p class="flex justify-between"><span>> URL ARCHIVES:</span> <span class="text-white font-bold">{{ $totalUrlArchives }}</span></p>
                <p class="flex justify-between"><span>> FILE ARCHIVES:</span> <span class="text-white font-bold">{{ $totalFileArchives }}</span></p>
                <p class="flex justify-between"><span>> PHYSICAL STORAGE:</span> <span class="text-white font-bold">{{ $totalPhysicalStorage }}</span></p>
            </div>
        </div>

        {{-- Box 2: Project Status (Kanan Atas) --}}
        <div class="lg:col-span-2 border border-border-color bg-surface p-4">
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
                            <div class="bg-primary h-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary">> No project data available to display.</p>
                @endforelse
            </div>
        </div>

        {{-- Box 3: Data Classification (Kiri Tengah) --}}
        <div class="lg:col-span-1 border border-border-color bg-surface p-4">
            <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> DATA CLASSIFICATION</h2>
            <div class="space-y-2 text-sm">
                @forelse($entityRankDistribution as $rank)
                    <p class="flex justify-between items-center">
                        <span class="flex items-center truncate">
                            <span class="w-4 mr-1 text-secondary">></span>
                            <span class="text-white truncate" title="{{ $rank->rank ?? 'N/A' }}">{{ $rank->rank ?? 'N/A' }}</span>
                        </span>
                        <span class="text-white font-bold ml-2">{{ $rank->total }}</span>
                    </p>
                @empty
                    <p class="text-secondary">> No entity rank data available.</p>
                @endforelse
            </div>
        </div>

        {{-- Box 4: Recent Activity (Kanan Tengah) --}}
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

        {{-- Box 5: System Alert (Kiri Bawah) --}}
        <div class="lg:col-span-1 border-2 border-yellow-500/50 bg-yellow-900/20 p-4 animate-pulse">
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

        {{-- Box 6: Agent Network (Kanan Bawah) --}}
        <div class="lg:col-span-2 border border-border-color bg-surface p-4">
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
</x-app-layout>