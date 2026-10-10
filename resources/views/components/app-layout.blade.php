@props(['title', 'theme' => 'default'])
<x-layout :title="$title ?? null" :theme="$theme">
    <x-slot:title>
        {{ $title ?? 'SECURE TERMINAL' }}
    </x-slot>

    <div x-data="{ sidebarOpen: false }">
        {{-- 
            - Mengganti 'min-h-screen' menjadi 'h-screen' untuk mengunci tinggi container ke tinggi viewport.
            - Menambahkan 'overflow-hidden' untuk mencegah scroll di level ini.
        --}}
        <div class="relative h-screen flex overflow-hidden bg-base">
            
            {{-- 'h-full' di dalam sidebar sekarang akan mengacu pada 'h-screen' dari parent ini --}}
            <div class="sidebar-fixed whitespace-nowrap">
                @include('layouts.partials.sidebar')
            </div>

            {{-- 
                - Menambahkan 'overflow-y-auto' ke wrapper konten utama.
                - Ini membuat HANYA area ini yang akan memiliki scrollbar jika kontennya panjang.
            --}}
            <div class="flex-1 flex flex-col main-content-wrapper overflow-y-auto">
                
                {{-- Topbar akan tetap "menempel" di atas karena struktur flex-col --}}
                @include('layouts.partials.topbar')
                
                {{-- Main content akan mengisi sisa ruang dan di-scroll di dalam wrapper-nya --}}
                <main class="flex-1 p-4 sm:p-6 min-w-0">
                    {{ $slot }}
                </main>
            </div>

            @include('layouts.partials.backdrop')
        </div>
    </div>

    {{-- Custom CSS (Tidak perlu diubah) --}}
    <style>  
        .main-content-wrapper {
            min-width: 0;
            width: 100%;
        }
        
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }
            
        @media (max-width: 768px) {
            .sidebar-fixed {
                position: fixed;
                z-index: 50;
                height: 100vh;
                height: 100dvh;                
                top: 0;
                left: 0;
            }
        }
    </style>

    {{-- REALTIME PRESENCE BOOT — berjalan di semua halaman yang sudah login. --}}
    <script>
        (function () {
            if (window.__bfPresenceBoot) return;
            window.__bfPresenceBoot = true;

            const heartbeatUrl = @json(route('agents.heartbeat'));
            const offlineUrl = @json(route('agents.offline'));
            const token = @json(csrf_token());

            function beat() {
                try {
                    fetch(heartbeatUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: '_token=' + encodeURIComponent(token),
                        cache: 'no-store',
                    }).catch(function () {});
                } catch (e) {}
            }

            function goOffline() {
                try {
                    const payload = new Blob(['_token=' + encodeURIComponent(token)], { type: 'application/x-www-form-urlencoded' });
                    if (navigator.sendBeacon) {
                        navigator.sendBeacon(offlineUrl, payload);
                    } else {
                        fetch(offlineUrl, {
                            method: 'POST',
                            keepalive: true,
                            credentials: 'include',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: '_token=' + encodeURIComponent(token),
                        }).catch(function () {});
                    }
                } catch (e) {}
            }

            // Heartbeat rutin: tab yang terbuka (bahkan di background) = terlihat online.
            beat();
            setInterval(beat, 30000);

            // Browser/tab ditutup atau navigasi keluar => langsung offline.
            window.addEventListener('pagehide', goOffline);

            // Kembali fokus ke aplikasi => kirim heartbeat langsung.
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') beat();
            });
        })();
    </script>
</x-layout>
