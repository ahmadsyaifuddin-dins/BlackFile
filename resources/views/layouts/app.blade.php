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
<body class="h-full font-mono bg-base text-secondary" x-data="{ sidebarOpen: false }">

<div class="flex h-full">
    <aside 
        class="w-64 bg-surface border-r border-border-color flex-col transition-transform duration-300 ease-in-out md:flex md:translate-x-0"
        :class="{'flex -translate-x-full': !sidebarOpen, 'absolute md:relative translate-x-0': sidebarOpen}"
    >
        <div class="p-4 text-2xl font-bold border-b border-border-color text-primary tracking-[.25em]">
            [B.F]
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded transition-colors {{ request()->is('dashboard') ? 'bg-surface-light text-primary' : 'hover:bg-surface-light hover:text-white' }}">
                <span>> Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded transition-colors {{ request()->is('friends*') ? 'bg-surface-light text-primary' : 'hover:bg-surface-light hover:text-white' }}">
                <span>> Friends Network</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded transition-colors {{ request()->is('projects*') ? 'bg-surface-light text-primary' : 'hover:bg-surface-light hover:text-white' }}">
                <span>> Projects</span>
            </a>
             <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded transition-colors {{ request()->is('logs*') ? 'bg-surface-light text-primary' : 'hover:bg-surface-light hover:text-white' }}">
                <span>> Activity Logs</span>
            </a>
        </nav>

        <div class="p-4 border-t border-border-color">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center space-x-3 px-3 py-2 rounded transition-colors hover:bg-red-900/50 hover:text-red-400">
                    <span>> Terminate Session</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col">
        
        <header class="bg-surface border-b border-border-color flex items-center justify-between px-4 py-3">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
                <h1 class="text-lg font-semibold text-white">> @yield('title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm text-primary">
                    {{ Auth::user()->role->alias }} — {{ Auth::user()->codename }}
                </span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d1117&color=2ea043&bold=true" 
                     class="w-8 h-8 rounded-full border-2 border-border-color">
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>