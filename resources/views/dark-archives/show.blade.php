<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $archive->title }} - BlackFile Archives</title>

    <!-- Tailwind v4 CDN + Typography Plugin -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>

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

        /* ATMOSPHERE: Noise Overlay */
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

        /* ATMOSPHERE: Scanlines */
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

        /* ANIMATION: Glitch Text */
        .glitch-wrapper {
            position: relative;
            display: inline-block;
        }

        .glitch-text::before,
        .glitch-text::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #030303;
        }

        .glitch-text::before {
            left: 2px;
            text-shadow: -1px 0 #ff0000;
            clip: rect(24px, 550px, 90px, 0);
            animation: glitch-anim-1 3s infinite linear alternate-reverse;
        }

        .glitch-text::after {
            left: -2px;
            text-shadow: -1px 0 #00ff00;
            clip: rect(85px, 550px, 140px, 0);
            animation: glitch-anim-2 2.5s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim-1 {
            0% {
                clip: rect(10px, 9999px, 30px, 0);
            }

            20% {
                clip: rect(80px, 9999px, 100px, 0);
            }

            100% {
                clip: rect(40px, 9999px, 60px, 0);
            }
        }

        @keyframes glitch-anim-2 {
            0% {
                clip: rect(90px, 9999px, 110px, 0);
            }

            100% {
                clip: rect(10px, 9999px, 30px, 0);
            }
        }


        /* Tambahkan CSS ini ke dalam tag <style> yang sudah ada */

        /* ============================================
   RESPONSIVE FIXES UNTUK MOBILE
   ============================================ */

        /* Mobile: 320px - 640px */
        @media (max-width: 640px) {

            /* Header - Title lebih kecil */
            .glitch-text {
                font-size: 1.5rem !important;
                /* 24px */
                line-height: 1.2;
                word-break: break-word;
            }

            /* Header padding */
            header {
                padding: 2rem 1rem !important;
            }

            /* Metadata grid - 2 kolom di mobile */
            header .grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1rem !important;
            }

            /* Admin button - full width di mobile */
            @supports selector(:has(*)) {
                header:has(.md\:absolute) {
                    padding-top: 3.5rem;
                }
            }

            /* Image height limit */
            .max-h-\[600px\] {
                max-height: 300px !important;
            }

            /* Article container padding */
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }

            /* Typography prose adjustments */
            .prose {
                font-size: 0.875rem !important;
                /* 14px */
            }

            .prose p {
                font-size: 0.875rem !important;
                line-height: 1.6;
                margin-bottom: 1rem;
            }

            .prose h2 {
                font-size: 1.25rem !important;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
            }

            .prose h3 {
                font-size: 1.125rem !important;
                margin-top: 1.25rem;
                margin-bottom: 0.5rem;
            }

            /* Blockquote */
            .prose blockquote {
                padding: 1rem !important;
                font-size: 0.8125rem !important;
                margin: 1rem 0;
            }

            /* Pay Respects Button */
            #respectBtn {
                padding: 0.75rem 1.5rem !important;
                font-size: 0.75rem !important;
            }

            #respectBtn svg {
                width: 1.25rem !important;
                height: 1.25rem !important;
            }

            /* Respect counters */
            .flex.items-center.space-x-6 {
                gap: 1rem !important;
            }

            /* Top bar spacing */
            .border-b-2.border-red-900\/50 {
                gap: 1rem !important;
            }
        }

        /* Tablet: 641px - 768px */
        @media (min-width: 641px) and (max-width: 768px) {
            .glitch-text {
                font-size: 2rem !important;
                /* 32px */
            }

            header {
                padding: 2.5rem 1.5rem !important;
            }

            .max-h-\[600px\] {
                max-height: 400px !important;
            }
        }

        /* Tablet Large: 769px - 1024px */
        @media (min-width: 769px) and (max-width: 1024px) {
            .glitch-text {
                font-size: 2.5rem !important;
                /* 40px */
            }

            .max-h-\[600px\] {
                max-height: 500px !important;
            }
        }

        /* Fix untuk glitch animation di mobile */
        @media (max-width: 640px) {

            .glitch-text::before,
            .glitch-text::after {
                font-size: 1.5rem;
            }
        }

        /* Ensure no horizontal overflow */
        * {
            box-sizing: border-box;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* Fix metadata text overflow */
        .text-gray-300 {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* TABLE RESPONSIVENESS */
        /* Wrapper untuk scroll horizontal di mobile */
        .prose table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.75rem;
        }

        .prose thead {
            background-color: #1a1a1a;
            color: #ef4444;
        }

        .prose th {
            padding: 0.75rem 0.5rem;
            text-align: left;
            font-weight: bold;
            border: 1px solid #374151;
            white-space: nowrap;
        }

        .prose td {
            padding: 0.75rem 0.5rem;
            border: 1px solid #374151;
            color: #9ca3af;
            vertical-align: top;
        }

        .prose tbody tr:nth-child(odd) {
            background-color: #0a0a0a;
        }

        .prose tbody tr:hover {
            background-color: #1f1f1f;
        }

        /* Mobile specific table styles */
        @media (max-width: 640px) {
            .prose table {
                font-size: 0.6875rem;
                /* 11px */
                display: block;
                border: 1px solid #374151;
            }

            .prose th,
            .prose td {
                padding: 0.5rem 0.375rem;
                font-size: 0.6875rem;
            }

            /* Add scroll indicator */
            .prose table::after {
                content: "→ Swipe to see more";
                position: absolute;
                right: 0;
                bottom: -1.5rem;
                font-size: 0.625rem;
                color: #6b7280;
                font-style: italic;
            }
        }

        /* Tablet styles */
        @media (min-width: 641px) and (max-width: 1024px) {
            .prose table {
                font-size: 0.8125rem;
            }

            .prose th,
            .prose td {
                padding: 0.625rem 0.5rem;
            }
        }
    </style>
</head>

<body class="antialiased selection:bg-red-900 selection:text-white">

    <div class="noise-overlay"></div>
    <div class="scanlines"></div>

    <!-- MAIN CONTAINER -->
    <div class="relative z-10 min-h-screen flex flex-col items-center">

        <!-- HEADER SECTION -->
        <!-- Padding diperketat: px-4 di mobile, px-6 di desktop -->
        <header
            class="w-full max-w-5xl mx-auto pt-8 pb-6 px-4 sm:pt-12 sm:pb-8 sm:px-6 border-x border-gray-900 bg-[#050505] relative">

            <!-- TOMBOL ADMIN (RESPONSIVE) -->
            @auth
                <div
                    class="w-full mb-4 md:mb-0 md:absolute md:top-6 md:right-6 md:w-auto z-50 flex justify-center md:justify-end">
                    <a href="{{ route('dark-archives.edit', $archive->slug) }}"
                        class="group flex items-center gap-2 bg-black/80 backdrop-blur border border-red-900/50 text-red-500 px-4 py-2 text-xs font-mono hover:bg-red-900 hover:text-white transition-all duration-300 shadow-[0_0_15px_rgba(220,38,38,0.3)]">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse group-hover:bg-white"></span>
                        [ ADMIN MODE: EDIT FILE ]
                    </a>
                </div>
            @endauth

            <!-- Top Bar -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-end border-b-2 border-red-900/50 pb-4 mb-6 sm:mb-8 gap-4">
                <div>
                    <span
                        class="block text-[10px] sm:text-xs text-red-600 font-bold tracking-[0.2em] sm:tracking-[0.3em] mb-1 animate-pulse">
                        CONFIDENTIAL // DECLASSIFIED
                    </span>
                    <h2 class="text-xs sm:text-sm text-gray-500 font-mono">CASE ID: {{ $archive->case_code }}</h2>
                </div>
                <div class="w-full md:w-auto text-left md:text-right">
                    <a href="{{ route('dark-archives.index') }}"
                        class="text-[10px] sm:text-xs text-gray-600 hover:text-red-500 transition tracking-widest font-mono">
                        [ RETURN TO INDEX ]
                    </a>
                </div>
            </div>

            <!-- Title & Glitch Effect -->
            <div class="glitch-wrapper mb-6 w-full">
                <!-- Judul Responsif: text-2xl di HP agar muat, break-words agar tidak bablas ke kanan -->
                <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-extrabold text-gray-200 tracking-tighter uppercase leading-tight sm:leading-none glitch-text font-['Special_Elite'] break-words hyphens-auto"
                    data-text="{{ $archive->title }}">
                    {{ $archive->title }}
                </h1>
            </div>

            <!-- Metadata Grid -->
            <!-- Font lebih kecil di mobile (text-[10px]) agar muat 2 kolom -->
            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-4 text-[10px] sm:text-xs font-mono text-gray-500 border-b border-gray-900 pb-8">
                <div>
                    <span class="block text-gray-700 uppercase">Incident Date</span>
                    <span class="text-gray-300">{{ $archive->formatted_date }}</span>
                </div>
                <div>
                    <span class="block text-gray-700 uppercase">Location</span>
                    <span class="text-gray-300">{{ $archive->location ?? 'UNKNOWN' }}</span>
                </div>
                <div>
                    <span class="block text-gray-700 uppercase">Archived By</span>
                    <span class="text-gray-300 break-all">AGENT
                        {{ strtoupper($archive->agent->name ?? 'REDACTED') }}</span>
                </div>
                <div>
                    <span class="block text-gray-700 uppercase">Impact</span>
                    <span class="text-red-900 font-bold">CRITICAL</span>
                </div>
            </div>
        </header>

        <!-- IMAGE EVIDENCE -->
        @if ($archive->thumbnail)
            <div class="w-full max-w-5xl mx-auto border-x border-gray-900 relative group overflow-hidden">
                <div class="absolute inset-0 bg-red-900/10 mix-blend-overlay z-10 pointer-events-none"></div>
                <img src="{{ asset($archive->thumbnail) }}"
                    class="w-full h-auto max-h-[600px] object-cover grayscale contrast-125 brightness-75 group-hover:grayscale-0 group-hover:brightness-90 transition duration-[3s] ease-in-out">
                <div
                    class="absolute bottom-4 right-4 z-20 bg-black/80 px-2 py-1 text-[10px] text-gray-400 border border-gray-700">
                    FIG 1.A - VISUAL EVIDENCE
                </div>
            </div>
        @endif

        <!-- ARTICLE CONTENT -->
        <!-- Padding diperketat: px-4 di mobile -->
        <main class="w-full max-w-5xl mx-auto px-4 py-8 sm:px-6 sm:py-12 border-x border-gray-900 bg-[#050505]">
            <!-- Prose Responsif: prose-sm di HP agar teks tidak terlalu besar, prose-lg di desktop -->
            <article
                class="prose prose-invert prose-sm sm:prose-lg max-w-none 
                prose-p:font-mono prose-p:text-gray-400 prose-p:leading-relaxed
                prose-headings:font-['Special_Elite'] prose-headings:text-gray-200 prose-headings:uppercase prose-headings:tracking-widest
                prose-strong:text-red-600 prose-strong:font-bold
                prose-a:text-red-500 hover:prose-a:text-white
                prose-blockquote:border-l-red-900 prose-blockquote:bg-gray-900/30 prose-blockquote:text-gray-500 prose-blockquote:italic prose-blockquote:p-4 sm:prose-blockquote:p-6
                ">
                {!! $archive->content !!}
            </article>

            <!-- ENGAGEMENT SECTION (RESPECTS) -->
            <div class="mt-16 sm:mt-24 pt-8 sm:pt-12 border-t border-gray-900 flex flex-col items-center text-center">
                <div class="mb-6 sm:mb-8 px-4">
                    <p class="text-xs sm:text-sm text-gray-600 italic font-serif">
                        "Those who cannot remember the past are condemned to repeat it."
                    </p>
                </div>

                <!-- Respect Button -->
                <button onclick="payRespect({{ $archive->id }})" id="respectBtn"
                    class="group relative inline-flex items-center justify-center px-8 sm:px-10 py-3 sm:py-4 overflow-hidden font-mono font-bold tracking-widest text-white transition-all duration-300 bg-gray-900 border border-gray-800 hover:bg-black hover:border-red-900 focus:outline-none text-xs sm:text-base">

                    <span
                        class="absolute top-0 left-0 w-full h-0 transition-all duration-500 ease-out bg-red-900 opacity-20 group-hover:h-full"></span>

                    <span class="relative flex items-center space-x-3">
                        <!-- Candle Icon -->
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 group-hover:text-red-500 transition-colors duration-500"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C11.5 2 11 2.5 11 3C11 3.5 11.5 4 12 4C12.5 4 13 3.5 13 3C13 2.5 12.5 2 12 2M12 5C10.3 5 9 6.3 9 8V20C9 21.1 9.9 22 11 22H13C14.1 22 15 21.1 15 20V8C15 6.3 13.7 5 12 5M12 7C12.6 7 13 7.4 13 8V10H11V8C11 7.4 11.4 7 12 7Z" />
                            <path class="animate-pulse origin-bottom" fill="#ef4444"
                                d="M12 0.5C11 2 10 3.5 10 5H14C14 3.5 13 2 12 0.5Z" />
                        </svg>
                        <span>PAY RESPECTS</span>
                    </span>
                </button>

                <!-- Feedback Message & Counters -->
                <div class="mt-6 font-mono text-[10px] sm:text-xs text-gray-500 space-y-2 w-full">
                    <p id="respectMessage" class="h-4 text-red-500 opacity-0 transition-opacity duration-500"></p>
                    <div class="flex items-center justify-center space-x-6 pt-4 border-t border-gray-900 w-48 mx-auto">
                        <span class="flex items-center" title="Total Views">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 text-gray-700" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ number_format($archive->views) }}
                        </span>
                        <span class="flex items-center" title="Respects Paid">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 text-gray-700" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" />
                            </svg>
                            <span id="respectCount">{{ number_format($archive->respects) }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <footer
            class="w-full max-w-5xl mx-auto py-8 text-center text-[10px] text-gray-800 font-mono border-t border-gray-900">
            BLACKFILE PROJECT // ARCHIVE SYSTEM V.2.0 // <span class="text-red-900">NO UNAUTHORIZED ACCESS</span>
        </footer>

    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function payRespect(id) {
            const btn = document.getElementById('respectBtn');
            const msg = document.getElementById('respectMessage');
            const countDisplay = document.getElementById('respectCount');

            btn.classList.add('opacity-50', 'cursor-not-allowed');

            fetch(`/dark-archives/respect/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    msg.innerText = data.message;
                    msg.classList.remove('opacity-0');

                    if (data.success) {
                        countDisplay.innerText = new Intl.NumberFormat().format(data.total);
                    }

                    setTimeout(() => {
                        msg.classList.add('opacity-0');
                    }, 4000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
        }
    </script>
</body>

</html>
