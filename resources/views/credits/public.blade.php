<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlackFile End Credits: {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400&display=swap');
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #000;
            color: #e5e7eb;
            text-align: center;
            overflow: hidden; /* Mencegah scrollbar muncul */
        }

        .credits-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            perspective: 400px;
        }

        .credits-scroll {
            position: absolute;
            top: -11%; /* POSISI INI TIDAK DIUBAH SESUAI PERMINTAAN */
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 600px;
            /* Animasi akan ditambahkan oleh JS */
        }

        @keyframes scroll-up {
            from { transform: translate(-50%, 0); }
            to { transform: translate(-50%, -100%); }
        }

        .credit-role {
            font-family: 'Orbitron', sans-serif;
            /* Ukuran font disesuaikan untuk mobile & desktop */
            font-size: 1rem; /* Ukuran dasar untuk mobile */
            color: #36d351; /* Warna hijau khas BlackFile */
            margin-bottom: 1.25rem; /* Jarak ke nama pertama sedikit ditambah */
            margin-top: 4rem; /* Jarak antar seksi diperlebar */
            text-transform: uppercase;
            letter-spacing: 0.1em;
            /* Efek glow halus */
            text-shadow: 0 0 8px rgba(54, 211, 81, 0.4);
        }

        .credit-name {
            /* Ukuran font, warna, dan bobot disesuaikan */
            font-size: 1.125rem; /* text-lg, lebih kecil dari sebelumnya */
            font-weight: 300; /* Font lebih tipis dan elegan */
            color: #d1d5db; /* gray-300, sedikit lebih lembut dari putih murni */
            margin-bottom: 0.75rem; /* Jarak antar nama sedikit ditambah */
            letter-spacing: 0.025em; /* Spasi antar huruf agar lebih mudah dibaca */
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.1); /* Efek glow sangat halus */
        }

        .logo-container {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.5rem;
        }
        .logo-img {
            height: 4.5rem;
        }
        
        /* Media query untuk layar lebih besar (desktop) */
        @media (min-width: 640px) {
            .credit-role {
                font-size: 1.25rem; /* Ukuran kembali normal di desktop */
            }
            .credit-name {
                font-size: 1.25rem; /* Ukuran kembali normal di desktop */
            }
        }

        /* Overlay untuk memulai interaksi */
        #start-overlay {
            position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.9);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 50; cursor: pointer; transition: opacity 0.5s ease-out;
        }
        #start-overlay.hidden { opacity: 0; pointer-events: none; }
        .play-icon {
            width: 80px; height: 80px; border: 2px solid #36d351; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; transition: transform 0.2s ease;
        }
        .play-icon:hover { transform: scale(1.1); background-color: rgba(54, 211, 81, 0.1); }
        .play-triangle {
            width: 0; height: 0; border-top: 20px solid transparent; border-bottom: 20px solid transparent;
            border-left: 30px solid #36d351; margin-left: 8px;
        }
    </style>
</head>
<body class="bg-black">

    <div id="start-overlay">
        <div class="play-icon">
            <div class="play-triangle"></div>
        </div>
        <p class="mt-4 text-lg font-['Orbitron'] text-gray-300">BEGIN TRANSMISSION</p>
    </div>

    <div class="credits-container">
        <div class="credits-scroll" id="credits-content">
            <div class="pt-[100vh]">
                <h1 class="text-4xl sm:text-5xl font-bold font-['Orbitron'] my-5 tracking-widest">BLACKFILE</h1>
                <img src="{{ asset('app-icon.png') }}" alt="app-icon" class="w-24 block mx-auto">
                @foreach($credits as $section)
                    <div class="my-8">
                        <p class="credit-role">{{ $section->role }}</p>
                        @foreach($section->names as $name)
                            <p class="credit-name">{{ $name }}</p>
                        @endforeach
                        @if(!empty($section->logos))
                            <div class="logo-container">
                                @foreach($section->logos as $logoPath)
                                    <img src="{{ url($logoPath) }}" alt="logo" class="logo-img">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="my-32 text-gray-500 text-sm">
                    <p>A BlackFile Internal Project</p>
                    <p>&copy; {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($musicCredit)
        <audio id="credits-audio" loop>
            <source src="{{ url($musicCredit->music_path) }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const startOverlay = document.getElementById('start-overlay');
            const audio = document.getElementById('credits-audio');
            const scrollContent = document.getElementById('credits-content');

            function startExperience() {
                startOverlay.classList.add('hidden');
                if (audio) {
                    audio.play().catch(error => {
                        console.warn("Autoplay was prevented by the browser.", error);
                    });
                }
                
                const totalHeight = scrollContent.scrollHeight;
                const viewportHeight = window.innerHeight;
                const scrollDistance = totalHeight + viewportHeight;
                const speed = 17; // Kecepatan sedikit disesuaikan untuk font baru
                const duration = scrollDistance / speed;
                
                scrollContent.style.animation = `scroll-up ${duration}s linear forwards`;
            }

            if (audio) {
                let playPromise = audio.play();
                if (playPromise !== undefined) {
                    playPromise.then(_ => {
                        startExperience();
                    }).catch(error => {
                        startOverlay.addEventListener('click', startExperience, { once: true });
                    });
                }
            } else {
                startOverlay.addEventListener('click', startExperience, { once: true });
            }
        });

        const style = document.createElement('style');
        style.textContent = `@keyframes scroll-up { from { transform: translate(-50%, 0); } to { transform: translate(-50%, -100%); } }`;
        document.head.appendChild(style);
    </script>

</body>
</html>
