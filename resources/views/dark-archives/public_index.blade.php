<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Dark Archives - Declassified Records</title>

    <!-- Tailwind v4 CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Special+Elite&display=swap"
        rel="stylesheet">

    <style>
        body {
            background-color: #030303;
            color: #c0c0c0;
            font-family: 'Courier Prime', monospace;
            overflow-x: hidden;
        }

        /* Noise Overlay */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 50;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
        }

        /* Scanlines */
        .scanlines {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0) 50%, rgba(0, 0, 0, 0.2) 50%, rgba(0, 0, 0, 0.2));
            background-size: 100% 4px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 40;
        }
    </style>
</head>

<body class="antialiased selection:bg-red-900 selection:text-white">

    <div class="noise-overlay"></div>
    <div class="scanlines"></div>

    <div class="relative z-10 min-h-screen p-6 md:p-12">
        <div class="max-w-7xl mx-auto">

            <!-- HEADER -->
            <div
                class="border-b-2 border-red-900/50 pb-6 mb-12 flex flex-col md:flex-row justify-between items-end gap-4">
                <div>
                    <h1
                        class="text-4xl md:text-6xl font-bold text-gray-100 tracking-tighter uppercase font-['Special_Elite']">
                        The Dark Archives
                    </h1>
                    <p class="text-sm text-red-600 tracking-[0.3em] mt-2 animate-pulse">DECLASSIFIED INDONESIAN
                        TRAGEDIES</p>
                </div>

                <div class="flex gap-4 text-xs font-mono">
                    <a href="{{ route('welcome') }}" class="text-gray-500 hover:text-white transition">[ HOME ]</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-green-600 hover:text-green-400 transition">[ AGENT
                            DASHBOARD ]</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-white transition">[ SECURE LOGIN
                            ]</a>
                    @endauth
                </div>
            </div>

            <!-- GRID CONTENT -->
            @if ($archives->isEmpty())
                <div class="text-center py-32 border border-gray-900 bg-[#050505]">
                    <p class="text-gray-600 text-sm tracking-widest">[ SYSTEM: NO DECLASSIFIED FILES FOUND ]</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($archives as $archive)
                        <a href="{{ route('dark-archives.show', $archive->slug) }}"
                            class="group block relative bg-[#050505] border border-gray-800 hover:border-red-800 transition-all duration-500 overflow-hidden shadow-lg hover:shadow-red-900/20">

                            <!-- Thumbnail Area -->
                            <div class="h-56 overflow-hidden relative">
                                @if ($archive->thumbnail)
                                    <img src="{{ asset($archive->thumbnail) }}"
                                        class="w-full h-full object-cover grayscale brightness-75 contrast-125 group-hover:grayscale-0 group-hover:scale-105 transition duration-700 ease-out">
                                @else
                                    <div
                                        class="w-full h-full bg-gray-900 flex items-center justify-center border-b border-gray-800">
                                        <span class="text-gray-700 text-xs font-mono">[ IMAGE REDACTED ]</span>
                                    </div>
                                @endif

                                <!-- Date Badge -->
                                <div
                                    class="absolute top-0 right-0 bg-black/80 text-white text-[10px] px-3 py-1 font-mono border-l border-b border-gray-800">
                                    {{ $archive->formatted_date }}
                                </div>
                            </div>

                            <!-- Text Content -->
                            <div class="p-6 relative">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[10px] text-red-700 font-bold uppercase tracking-widest">
                                        {{ $archive->case_code }}
                                    </span>
                                    <div class="flex items-center space-x-2 text-[10px] text-gray-600">
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ $archive->views }}
                                        </span>
                                    </div>
                                </div>

                                <h3
                                    class="text-xl font-bold text-gray-300 group-hover:text-red-500 transition font-['Special_Elite'] uppercase mb-4 leading-tight">
                                    {{ $archive->title }}
                                </h3>

                                <div class="pt-4 border-t border-gray-900 flex justify-between items-center">
                                    <span
                                        class="text-[10px] text-gray-600 group-hover:text-gray-400 transition font-mono">
                                        ACCESS FILE_
                                    </span>
                                    <span
                                        class="text-red-900 group-hover:text-red-600 transition transform group-hover:translate-x-1">
                                        >>>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- FOOTER -->
            <div class="mt-20 pt-8 border-t border-gray-900 text-center text-[10px] text-gray-700 font-mono">
                <p>BLACKFILE PROJECT // PUBLIC ACCESS TERMINAL</p>
                <p class="mt-1">&copy; {{ date('Y') }} ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </div>
</body>

</html>
