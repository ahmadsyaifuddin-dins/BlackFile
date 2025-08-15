<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLACKFILE // @yield('title', 'SECURE TERMINAL')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-mono bg-base text-secondary" x-data="{ sidebarOpen: false }">

    <div class="relative min-h-screen flex">

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col">

            @include('layouts.partials.topbar')

            <main class="p-4 sm:p-6">
                @yield('content')
            </main>

        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-10 md:hidden" aria-hidden="true"></div>
        </div>
</body>

</html>