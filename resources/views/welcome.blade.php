<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BLACKFILE // SECURE SYSTEM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-mono bg-base text-secondary bg-[#000]">
    <div class="relative min-h-screen">
        <header class="absolute top-0 left-0 right-0 z-10 p-4">
            <div class="container mx-auto flex justify-between items-center">
                <span class="text-2xl font-bold text-primary tracking-[.25em]">[B.F]</span>
                
                {{-- ================================================================ --}}
                {{-- == PERUBAHAN: Tombol Header Pintar == --}}
                {{-- ================================================================ --}}
                @auth
                    {{-- Tombol ini tampil JIKA pengguna SUDAH LOGIN --}}
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-primary text-primary hover:bg-primary hover:text-base transition-colors font-bold text-sm">
                        > ENTER DASHBOARD
                    </a>
                @else
                    {{-- Tombol ini tampil JIKA pengguna BELUM LOGIN --}}
                    <a href="{{ route('login') }}" class="px-4 py-2 border border-primary text-primary hover:bg-primary hover:text-base transition-colors font-bold text-sm">
                        > AUTHENTICATE
                    </a>
                @endauth
            </div>
        </header>

        <main>
            <section class="min-h-screen flex items-center justify-center text-center p-4">
                <div x-data="{ showCursor: true }" x-init="setInterval(() => showCursor = !showCursor, 600)">
                    <div class="mb-4 flex justify-center">
                        <img src="{{ asset('app-icon.png') }}" alt="App Icon BlackFile" class="w-32 h-32">
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white mt-4 tracking-wider">
                        BLACKFILE PROTOCOL
                        <span x-show="showCursor" class="animate-pulse">_</span>
                    </h1>
                    <p class="mt-4 max-w-2xl mx-auto text-sm sm:text-base">
                        A secure, encrypted, and decentralized archive for sensitive field operations and agent network management.
                    </p>
                    <p class="mt-2 text-red-500/70 text-xs">[UNAUTHORIZED ACCESS IS STRICTLY PROHIBITED]</p>
                </div>
            </section>

            <section class="py-20 bg-surface border-y-2 border-border-color p-4">
                <div class="container mx-auto">
                    <h2 class="text-center text-3xl font-bold text-primary mb-12">[ SYSTEM CAPABILITIES ]</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div class="border border-border-color p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-white mb-2">> AGENT ROSTER</h3>
                            <p class="text-sm">Securely manage operative profiles, designations, and clearance levels. On-demand deployment and extraction protocols.</p>
                        </div>
                        <div class="border border-border-color p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-white mb-2">> MISSION DIRECTIVES</h3>
                            <p class="text-sm">Create, assign, and monitor multi-phase operations. Real-time status tracking from inception to archival.</p>
                        </div>
                        <div class="border border-border-color p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-white mb-2">> ASSET NETWORK</h3>
                            <p class="text-sm">Map and visualize hierarchical relationships between operatives, informants, and key assets in the field.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-20 text-center p-4">
                <div class="container mx-auto">
                    <h2 class="text-2xl font-bold text-white">Your mission, should you choose to accept it, begins here.</h2>
                    <p class="mt-4 mb-8 max-w-xl mx-auto">This terminal is the gateway. Verify your identity to proceed.</p>
                    
                    {{-- ================================================================ --}}
                    {{-- == PERUBAHAN: Tombol CTA Pintar == --}}
                    {{-- ================================================================ --}}
                    @auth
                        {{-- Tombol ini tampil JIKA pengguna SUDAH LOGIN --}}
                        <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-primary text-primary transition-colors font-bold tracking-widest text-lg hover:bg-primary-hover">
                            > PROCEED TO DASHBOARD
                        </a>
                    @else
                        {{-- Tombol ini tampil JIKA pengguna BELUM LOGIN --}}
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-primary text-primary transition-colors font-bold tracking-widest text-lg hover:bg-primary-hover">
                            > ACCESS TERMINAL
                        </a>
                    @endauth
                </div>
            </section>
        </main>

        <footer class="text-center p-4 border-t border-border-color bg-surface">
            <p class="text-xs text-secondary/50">&copy; <span class="font-bold"> 2025 - {{ date('Y') }} </span> Directorate Internal Systems // All communications are monitored and encrypted.</p>
        </footer>
    </div>
</body>
</html>
