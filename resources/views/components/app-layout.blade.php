<x-layout>
    <x-slot:title>
        {{ $title ?? 'SECURE TERMINAL' }}
    </x-slot>

    <div x-data="{ sidebarOpen: false }">
        <div class="relative min-h-screen flex">

            @include('layouts.partials.sidebar')

            <div class="flex-1 flex flex-col">
                @include('layouts.partials.topbar')
                <main class="p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>

            @include('layouts.partials.backdrop')
            
        </div>
    </div>
</x-layout>