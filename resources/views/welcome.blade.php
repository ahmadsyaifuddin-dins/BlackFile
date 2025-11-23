<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>BLACKFILE // SECURE ACCESS</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700;800&family=Roboto+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'JetBrains Mono', 'Roboto Mono', monospace;
            background-color: #050505;
            /* Mencegah scroll horizontal di mobile akibat elemen absolute */
            overflow-x: hidden;
        }

        .bg-grid {
            background-size: 30px 30px;
            /* Ukuran grid lebih kecil untuk mobile */
            background-image: linear-gradient(to right, rgba(var(--primary-rgb, 34, 197, 94), 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(var(--primary-rgb, 34, 197, 94), 0.05) 1px, transparent 1px);
        }

        /* Di layar besar, grid lebih lega */
        @media (min-width: 768px) {
            .bg-grid {
                background-size: 50px 50px;
            }
        }

        .vignette {
            background: radial-gradient(circle, transparent 40%, #000000 100%);
        }

        .scanlines {
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0),
                    rgba(255, 255, 255, 0) 50%,
                    rgba(0, 0, 0, 0.15) 50%,
                    /* Opacity dikurangi agar tidak ganggu baca di HP */
                    rgba(0, 0, 0, 0.15));
            background-size: 100% 3px;
            pointer-events: none;
        }
    </style>
</head>

<body class="antialiased text-secondary selection:bg-primary selection:text-black">

    {{-- Background Layers --}}
    <div class="fixed inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="fixed inset-0 vignette z-0 pointer-events-none"></div>
    <div class="fixed inset-0 scanlines z-50 pointer-events-none opacity-40"></div>

    <div class="relative min-h-screen flex flex-col z-10">

        {{-- HEADER --}}
        {{-- Padding dikurangi untuk mobile (p-4), di desktop p-6 --}}
        <header
            class="w-full px-4 py-4 md:p-6 border-b border-primary/10 bg-black/80 backdrop-blur-md fixed top-0 z-40 transition-all">
            <div class="container mx-auto flex justify-between items-center">
                {{-- Logo Area --}}
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="w-1.5 h-1.5 md:w-2 md:h-2 bg-primary rounded-full animate-pulse"></div>
                    <span class="text-lg md:text-xl font-bold text-primary tracking-widest group cursor-default">
                        [B.F]<span
                            class="hidden sm:inline opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-xs tracking-normal ml-2 text-secondary">_SYS_ONLINE</span>
                    </span>
                </div>

                {{-- Auth Status --}}
                <div class="flex items-center gap-4">
                    @auth
                        {{-- Nama User hanya tampil di Tablet ke atas (sm:block) --}}
                        <div class="text-xs text-right hidden sm:block">
                            <div class="text-secondary opacity-60">ID</div>
                            <div class="text-primary font-bold">{{ Str::limit(Auth::user()->name, 10) }}</div>
                        </div>
                        <a href="{{ route('dashboard') }}"
                            class="relative px-3 py-1.5 md:px-5 md:py-2 border border-primary bg-primary/10 text-primary font-bold text-[10px] md:text-xs uppercase tracking-wider hover:bg-primary hover:text-black transition-all duration-300">
                            > DASHBOARD
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="relative px-3 py-1.5 md:px-5 md:py-2 border border-secondary/50 text-secondary font-bold text-[10px] md:text-xs uppercase tracking-wider hover:border-primary hover:text-primary transition-colors duration-300">
                            LOGIN
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-grow flex flex-col pt-16 md:pt-20">

            {{-- HERO SECTION --}}
            {{-- Menggunakan min-h-[dynamic] agar pas di layar HP --}}
            <section
                class="flex flex-col items-center justify-center text-center px-4 py-12 md:py-20 min-h-[60vh] md:min-h-[70vh]">

                {{-- Logo --}}
                <div class="relative mb-6 md:mb-8 group">
                    <div
                        class="absolute inset-0 bg-primary/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                    </div>
                    <img src="{{ asset('app-icon.png') }}" alt="BlackFile"
                        class="w-24 h-24 md:w-32 md:h-32 relative z-10 drop-shadow-[0_0_15px_rgba(var(--primary-rgb,34,197,94),0.3)] grayscale group-hover:grayscale-0 transition-all duration-500">
                </div>

                {{-- Typing Effect Title --}}
                {{-- min-h-[4rem] ditambahkan agar layout tidak loncat saat teks berganti --}}
                <div x-data="{
                    text: '',
                    textArray: ['BLACKFILE_PROTOCOL', 'SECURE_ARCHIVE', 'EYES_ONLY'],
                    typeSpeed: 80,
                    deleteSpeed: 40,
                    waitBeforeDelete: 2000,
                    textIndex: 0,
                    charIndex: 0,
                    isDeleting: false,
                    type() {
                        const currentText = this.textArray[this.textIndex];
                        if (this.isDeleting) {
                            this.text = currentText.substring(0, this.charIndex - 1);
                            this.charIndex--;
                        } else {
                            this.text = currentText.substring(0, this.charIndex + 1);
                            this.charIndex++;
                        }
                
                        if (!this.isDeleting && this.charIndex === currentText.length) {
                            this.isDeleting = true;
                            setTimeout(() => this.type(), this.waitBeforeDelete);
                        } else if (this.isDeleting && this.charIndex === 0) {
                            this.isDeleting = false;
                            this.textIndex = (this.textIndex + 1) % this.textArray.length;
                            setTimeout(() => this.type(), 500);
                        } else {
                            setTimeout(() => this.type(), this.isDeleting ? this.deleteSpeed : this.typeSpeed);
                        }
                    }
                }" x-init="type()"
                    class="min-h-[3rem] md:min-h-[4rem] flex items-center justify-center max-w-full overflow-hidden">

                    {{-- Font size responsif: text-2xl di HP, text-5xl di desktop. break-all mencegah overflow horizontal --}}
                    <h1
                        class="text-2xl sm:text-4xl md:text-6xl font-extrabold text-white tracking-tight break-words mx-2">
                        <span x-text="text"></span><span class="animate-pulse text-primary">_</span>
                    </h1>
                </div>

                <p
                    class="mt-4 md:mt-6 max-w-xs sm:max-w-2xl mx-auto text-xs sm:text-base text-secondary/80 leading-relaxed border-l-2 border-primary/30 pl-3 md:pl-4 text-left font-mono">
                    System designed for decentralized intelligence management. Secure operations in an encrypted
                    environment.
                </p>

                <div
                    class="mt-8 inline-flex items-center gap-2 px-3 py-1.5 rounded bg-red-900/20 border border-red-500/30 text-red-400 text-[10px] md:text-xs font-bold tracking-widest animate-pulse">
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    UNAUTHORIZED ACCESS PROHIBITED
                </div>
            </section>

            {{-- CAPABILITIES GRID --}}
            <section class="py-12 md:py-16 bg-black/40 border-y border-primary/20 backdrop-blur-sm">
                <div class="container mx-auto px-4">
                    <div class="flex items-center justify-center mb-8 md:mb-12 gap-2 md:gap-4">
                        <div class="h-px bg-primary/30 w-8 md:w-16"></div>
                        <h2 class="text-base md:text-xl font-bold text-primary tracking-widest uppercase">System
                            Capabilities</h2>
                        <div class="h-px bg-primary/30 w-8 md:w-16"></div>
                    </div>

                    {{-- Grid 1 kolom di HP, 3 di Desktop. Gap diperkecil di HP --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">

                        {{-- Card Component --}}
                        @foreach ([['title' => 'AGENT ROSTER', 'desc' => 'Manage operative designations and clearance levels.'], ['title' => 'MISSION LOGS', 'desc' => 'Detailed operational history and deployment tracking.'], ['title' => 'INTEL NETWORK', 'desc' => 'Visualize connections between assets and targets.']] as $item)
                            <div
                                class="group relative p-5 md:p-6 border border-primary/20 bg-black/40 hover:bg-primary/5 transition-all duration-300">
                                {{-- Corner Accents (Hanya muncul 4 sudut) --}}
                                <div
                                    class="absolute top-0 left-0 w-2 h-2 border-t border-l border-primary opacity-50 group-hover:opacity-100">
                                </div>
                                <div
                                    class="absolute top-0 right-0 w-2 h-2 border-t border-r border-primary opacity-50 group-hover:opacity-100">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-primary opacity-50 group-hover:opacity-100">
                                </div>
                                <div
                                    class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-primary opacity-50 group-hover:opacity-100">
                                </div>

                                <h3
                                    class="text-sm md:text-lg font-bold text-white mb-2 md:mb-3 flex items-center gap-2">
                                    <span class="text-primary">></span> {{ $item['title'] }}
                                </h3>
                                <p
                                    class="text-xs md:text-sm text-secondary/70 group-hover:text-secondary transition-colors">
                                    {{ $item['desc'] }}
                                </p>
                            </div>
                        @endforeach

                    </div>
                </div>
            </section>

            {{-- CTA SECTION --}}
            <section class="py-12 md:py-20 text-center px-4">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-xl md:text-3xl font-bold text-white mb-4 md:mb-6">INITIATE CONNECTION</h2>
                    <p class="mb-8 md:mb-10 text-xs md:text-base text-secondary/60">
                        Secure channel available. Identify yourself.
                    </p>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 border border-primary bg-primary/10 text-primary font-bold text-sm md:text-lg tracking-[0.1em] md:tracking-[0.2em] hover:bg-primary hover:text-black hover:shadow-[0_0_20px_rgba(34,197,94,0.5)] transition-all duration-300">
                            [ ENTER_VAULT ]
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 md:px-8 md:py-4 border border-white/20 bg-transparent text-white font-bold text-sm md:text-lg tracking-[0.1em] md:tracking-[0.2em] hover:border-primary hover:text-primary hover:bg-black hover:shadow-[0_0_20px_rgba(34,197,94,0.3)] transition-all duration-300">
                            [ ACCESS_TERMINAL ]
                        </a>
                    @endauth
                </div>
            </section>
        </main>

        {{-- FOOTER --}}
        <footer class="border-t border-primary/20 bg-black p-4 md:p-6 z-40">
            <div
                class="container mx-auto flex flex-col md:flex-row justify-between items-center text-[10px] md:text-xs font-mono text-secondary/40 text-center md:text-left gap-2 md:gap-0">
                <div>
                    &copy; 2025 - {{ date('Y') }} DIRECTORATE SYSTEMS
                </div>
                <div class="flex gap-3 md:gap-4">
                    <span>STATUS: <span class="text-green-500">SECURE</span></span>
                    <span>SERVER: <span class="text-primary">BF-01</span></span>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
