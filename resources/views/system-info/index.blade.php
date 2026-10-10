<x-app-layout title="System Information">
    <div class="font-mono max-w-7xl mx-auto" x-data="{
        now: new Date(),
        get clock() {
            return this.now.toLocaleTimeString('id-ID', { hour12: false });
        },
        get date() {
            return this.now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }
    }" x-init="setInterval(() => now = new Date(), 1000)">

        {{-- ===================== HEADER ===================== --}}
        <div class="relative border-y-2 border-dashed border-primary/50 py-5 mb-8 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-primary tracking-widest font-mono text-glow">
                    &gt; SYSTEM INFORMATION
                </h1>
                <p class="text-sm text-secondary mt-1">{{ __('Identitas, versioning, dan telemetri inti BlackFile.') }}</p>
            </div>
            <div class="flex items-center gap-2 px-3 py-2 border border-primary/40 bg-primary/5 rounded">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary"></span>
                </span>
                <span class="text-[10px] tracking-[0.3em] text-primary font-bold">{{ __('SYSTEM ONLINE') }}</span>
            </div>
        </div>

        {{-- ===================== IDENTITY HERO ===================== --}}
        <section class="relative overflow-hidden border border-primary/20 bg-[var(--color-surface)] mb-8">
            <div class="absolute top-0 left-0 w-5 h-5 border-t-2 border-l-2 border-primary/60"></div>
            <div class="absolute top-0 right-0 w-5 h-5 border-t-2 border-r-2 border-primary/60"></div>
            <div class="absolute bottom-0 left-0 w-5 h-5 border-b-2 border-l-2 border-primary/60"></div>
            <div class="absolute bottom-0 right-0 w-5 h-5 border-b-2 border-r-2 border-primary/60"></div>
            <div class="absolute inset-0 opacity-[0.05] pointer-events-none"
                style="background-image: linear-gradient(var(--color-primary) 1px, transparent 1px), linear-gradient(90deg, var(--color-primary) 1px, transparent 1px); background-size: 40px 40px;"></div>

            <div class="relative grid grid-cols-1 md:grid-cols-[auto_1fr_auto] gap-6 items-center p-6 md:p-8">
                {{-- Logo --}}
                <div class="flex items-center justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-full"></div>
                        <img src="{{ asset('app-icon.png') }}" alt="BlackFile"
                            class="relative z-10 w-24 h-24 drop-shadow-[0_0_18px_rgba(46,160,67,0.55)]">
                    </div>
                </div>

                {{-- Identity --}}
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-3 flex-wrap">
                        <h2 class="text-3xl md:text-4xl font-bold text-primary tracking-[.2em] text-glow">{{ strtoupper($project['name'] ?? 'BLACKFILE') }}</h2>
                        <span class="text-[10px] px-2 py-1 border border-primary/50 text-primary rounded tracking-widest">{{ $project['channel'] ?? 'STABLE' }}</span>
                    </div>
                    <p class="text-xs tracking-[0.35em] text-secondary/70 uppercase mt-2">// {{ $project['codename'] ?? 'SECURE TERMINAL' }}</p>
                    <p class="text-sm text-secondary leading-relaxed mt-4 max-w-2xl">{{ $project['description'] ?? '' }}</p>
                </div>

                {{-- Version Badge --}}
                <div class="justify-self-center md:justify-self-end">
                    <div class="relative border border-primary/40 bg-black/40 px-6 py-4 rounded text-center min-w-[150px]">
                        <span class="block text-[10px] tracking-[0.3em] text-secondary uppercase mb-1">{{ __('Current Version') }}</span>
                        <span class="block text-3xl font-bold text-primary text-glow">v{{ $project['version'] ?? '0.0.0' }}</span>
                        <span class="block text-[10px] tracking-widest text-secondary/60 uppercase mt-1">{{ __('Build') }} {{ $releasedAt->format('Y.m.d') }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== PROJECT ORIGIN ===================== --}}
        <section class="mb-8">
            <h2 class="text-lg font-bold text-primary border-b-2 border-border-color pb-2 mb-4 tracking-widest">
                // {{ __('PROJECT ORIGIN') }}
            </h2>

            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Datetime breakdown --}}
                <div class="lg:col-span-2 relative overflow-hidden border border-primary/20 bg-[var(--color-surface)] p-6">
                    <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-primary/50"></div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-primary/50"></div>

                    <p class="text-[11px] tracking-[0.3em] text-secondary/60 uppercase mb-4">{{ __('Initialized On') }}</p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach ([
                            ['HARI', $projectOrigin['day']],
                            ['TANGGAL', $projectOrigin['date']],
                            ['BULAN', $projectOrigin['month']],
                            ['TAHUN', $projectOrigin['year']],
                            ['JAM', $projectOrigin['time']],
                            ['DETIK', $projectOrigin['seconds']],
                        ] as [$label, $value])
                            <div class="text-center border border-primary/15 bg-black/30 py-3 px-2 rounded hover:border-primary/50 transition-colors">
                                <span class="block text-[9px] tracking-[0.25em] text-secondary/50 uppercase">{{ $label }}</span>
                                <span class="block text-lg md:text-xl font-bold text-white mt-1 truncate">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 pt-4 border-t border-primary/10 flex items-center gap-2 text-sm text-secondary">
                        <i class="fa-solid fa-calendar-check text-primary"></i>
                        <span>{{ $projectOrigin['full'] }} &mdash; {{ $projectOrigin['time'] }}:{{ $projectOrigin['seconds'] }}</span>
                    </div>
                </div>

                {{-- Uptime + Live Clock --}}
                <div class="relative border border-primary/20 bg-[var(--color-surface)] p-6 flex flex-col justify-between">
                    <div>
                        <p class="text-[11px] tracking-[0.3em] text-secondary/60 uppercase mb-3">{{ __('Uptime Since Initialization') }}</p>
                        <p class="text-2xl font-bold text-primary text-glow leading-snug">{{ $uptime }}</p>
                        <p class="text-xs text-secondary/70 mt-2">{{ __('Perjalanan BlackFile sejak commit pertama.') }}</p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-primary/10">
                        <p class="text-[11px] tracking-[0.3em] text-secondary/60 uppercase mb-1">{{ __('Live System Clock') }}</p>
                        <p class="text-2xl font-bold text-white tracking-widest" x-text="clock"></p>
                        <p class="text-xs text-secondary/70 mt-1" x-text="date"></p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== ENVIRONMENT ===================== --}}
        <section class="mb-8">
            <h2 class="text-lg font-bold text-primary border-b-2 border-border-color pb-2 mb-4 tracking-widest">
                // {{ __('RUNTIME ENVIRONMENT') }}
            </h2>

            <div class="grid md:grid-cols-2 gap-x-6 border border-primary/20 bg-[var(--color-surface)] p-2">
                @php
                    $envRows = [
                        'APP NAME' => $environment['app_name'],
                        'LARAVEL' => $environment['laravel'],
                        'PHP VERSION' => $environment['php'],
                        'ENVIRONMENT' => strtoupper($environment['environment']),
                        'DEBUG MODE' => $environment['debug'],
                        'DATABASE' => $environment['database'],
                        'DATABASE NAME' => $environment['database_name'],
                        'TIMEZONE' => $environment['timezone'],
                        'LOCALE' => strtoupper($environment['locale']),
                        'SERVER' => $environment['server'],
                        'PLATFORM' => $environment['os'],
                    ];
                @endphp

                @foreach ($envRows as $key => $value)
                    <div class="flex items-center justify-between gap-4 px-4 py-3 border-b border-primary/10 last:border-b-0 hover:bg-primary/5 transition-colors">
                        <span class="text-[11px] tracking-[0.2em] text-secondary/60 uppercase">{{ $key }}</span>
                        <span class="text-sm font-bold text-white truncate text-right">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ===================== DATA MATRIX ===================== --}}
        <section class="mb-8">
            <h2 class="text-lg font-bold text-primary border-b-2 border-border-color pb-2 mb-4 tracking-widest">
                // {{ __('DATA MATRIX') }}
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($stats as $stat)
                    <div class="group relative overflow-hidden border border-primary/20 bg-[var(--color-surface)] p-4 hover:border-primary/60 transition-all duration-300">
                        <div class="absolute -right-6 -top-6 w-16 h-16 bg-primary/5 rounded-full group-hover:bg-primary/10 transition-colors"></div>
                        <i class="fa-solid {{ $stat['icon'] }} text-primary text-lg mb-3"></i>
                        <p class="text-2xl md:text-3xl font-bold text-white">{{ number_format($stat['value']) }}</p>
                        <p class="text-[10px] tracking-[0.2em] text-secondary/60 uppercase mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ===================== BUILD TELEMETRY / FOOTER ===================== --}}
        <section class="relative border border-primary/20 bg-[var(--color-surface)] p-6 mb-4">
            <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-primary/50"></div>
            <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-primary/50"></div>

            <div class="grid sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] tracking-[0.25em] text-secondary/50 uppercase mb-1">{{ __('Author') }}</p>
                    <p class="text-sm font-bold text-white">{{ $project['author'] ?? 'Not Available' }}</p>
                </div>
                <div>
                    <p class="text-[10px] tracking-[0.25em] text-secondary/50 uppercase mb-1">{{ __('Last Build') }}</p>
                    <p class="text-sm font-bold text-white">{{ $releasedAt->translatedFormat('d F Y — H:i') }}</p>
                    <p class="text-[10px] text-secondary/50 mt-0.5">{{ $lastRelease }} {{ __('lalu') }}</p>
                </div>
                <div>
                    <p class="text-[10px] tracking-[0.25em] text-secondary/50 uppercase mb-1">{{ __('Repository') }}</p>
                    @if (!empty($project['repository']))
                        <a href="{{ $project['repository'] }}" target="_blank" rel="noopener"
                            class="text-sm font-bold text-primary hover:text-primary-hover transition-colors break-all inline-flex items-center gap-2">
                            <i class="fa-brands fa-github"></i> {{ __('View Source') }}
                        </a>
                    @else
                        <p class="text-sm font-bold text-white">Not Available</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-primary/10 text-center">
                <span class="text-[10px] text-secondary/30 tracking-[0.3em] uppercase">
                    {{ strtoupper($project['name'] ?? 'BLACKFILE') }} &middot; v{{ $project['version'] ?? '0.0.0' }} &middot; SECURE CONNECTION ESTABLISHED
                </span>
            </div>
        </section>
    </div>
</x-app-layout>
