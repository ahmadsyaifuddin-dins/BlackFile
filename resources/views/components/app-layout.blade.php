@props(['title', 'theme' => 'default'])
<x-layout :title="$title ?? null" :theme="$theme">
    <x-slot:title>
        {{ $title ?? 'SECURE TERMINAL' }}
    </x-slot>

    <div x-data="{ sidebarOpen: false }">
        <div class="relative min-h-screen flex">
            {{-- Sidebar dengan lebar tetap dan tidak terpengaruh overflow --}}
            <div class="sidebar-fixed">
                @include('layouts.partials.sidebar')
            </div>

            {{-- Main content area --}}
            <div class="flex-1 flex flex-col main-content-wrapper">
                @include('layouts.partials.topbar')
                
                {{-- Main content with proper overflow handling --}}
                <main class="flex-1 p-4 sm:p-6 min-w-0">
                    {{ $slot }}
                </main>
            </div>

            @include('layouts.partials.backdrop')
        </div>
    </div>

    {{-- Add custom CSS --}}
    <style>   
        /* Main content should be able to shrink */
        .main-content-wrapper {
            min-width: 0;
            width: 100%;
        }
        
        /* Table responsive container */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }
           
        @media (max-width: 768px) {
            .sidebar-fixed {
                position: fixed;
                z-index: 50;
                height: 100vh;
                top: 0;
                left: 0;
            }
        }
    </style>
</x-layout>