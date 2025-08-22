@props(['title', 'theme' => 'default'])
<x-layout :title="$title ?? null" :theme="$theme">
    <x-slot:title>
        {{ $title ?? 'SECURE TERMINAL' }}
    </x-slot>

    <div x-data="{ sidebarOpen: false }">
        {{-- 
            PERUBAHAN KUNCI #1:
            - Mengganti 'min-h-screen' menjadi 'h-screen' untuk mengunci tinggi container ke tinggi viewport.
            - Menambahkan 'overflow-hidden' untuk mencegah scroll di level ini.
        --}}
        <div class="relative h-screen flex overflow-hidden bg-base">
            
            {{-- Sidebar (Tidak perlu diubah) --}}
            {{-- 'h-full' di dalam sidebar sekarang akan mengacu pada 'h-screen' dari parent ini --}}
            <div class="sidebar-fixed whitespace-nowrap">
                @include('layouts.partials.sidebar')
            </div>

            {{-- 
                PERUBAHAN KUNCI #2:
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
</x-layout>
