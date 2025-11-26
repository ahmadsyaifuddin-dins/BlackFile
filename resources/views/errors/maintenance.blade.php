<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYSTEM LOCKDOWN | BlackFile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #050505;
            color: #e5e5e5;
            font-family: 'Courier New', Courier, monospace;
        }

        .glitch {
            position: relative;
            color: #ff0000;
            font-weight: bold;
        }

        .scanline {
            width: 100%;
            height: 100px;
            z-index: 10;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0) 0%, rgba(255, 0, 0, 0.1) 50%, rgba(0, 0, 0, 0) 100%);
            opacity: 0.1;
            position: absolute;
            bottom: 100%;
            animation: scanline 10s linear infinite;
            pointer-events: none;
        }

        @keyframes scanline {
            0% {
                bottom: 100%;
            }

            100% {
                bottom: -100%;
            }
        }
    </style>
</head>

<body class="h-screen flex items-center justify-center overflow-hidden relative">
    <div class="scanline"></div>

    <div class="text-center max-w-2xl p-6 border border-red-900 bg-black/50 rounded shadow-[0_0_15px_rgba(255,0,0,0.3)]">
        <h1 class="text-3xl md:text-6xl mb-4 glitch tracking-widest">[ LOCKDOWN ]</h1>
        <div class="h-px w-full bg-red-900 mb-6"></div>

        <p class="text-lg mb-6 text-red-400">
            SYSTEM MAINTENANCE PROTOCOL INITIATED.
        </p>

        <p class="text-gray-400 text-sm mb-8 leading-relaxed">
            Akses ke BlackFile ditutup sementara untuk peningkatan infrastruktur keamanan.
            <br>
            Semua jalur komunikasi dienkripsi ulang. Harap tunggu instruksi selanjutnya melalui Email Secure Channel.
        </p>

        <div class="flex flex-col gap-3 justify-center items-center">
            <span class="animate-pulse text-red-600 text-xs">
                CONNECTING TO SERVER... REFUSED
            </span>

            {{-- Tombol Logout agar mereka tidak stuck --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-gray-500 hover:text-white underline decoration-dotted">
                    [ TERMINATE SESSION / LOGOUT ]
                </button>
            </form>
        </div>
    </div>
</body>

</html>
