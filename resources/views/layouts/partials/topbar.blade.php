<header class="bg-surface border-b border-border-color flex items-center justify-between px-4 py-3">
    <div class="flex items-center space-x-3 min-w-0">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-white focus:outline-none flex-shrink-0">
            <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            <svg x-show="sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <h1 class="text-lg font-semibold text-white truncate hidden sm:block">
            > @yield('title', $title ?? 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center">
        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 group max-w-[60vw] sm:max-w-none">
            <span class="hidden sm:block text-sm text-secondary group-hover:text-primary transition-colors truncate">
                {{ Auth::user()->role->alias }} — {{ Auth::user()->codename }}
            </span>
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d1117&color=2ea043&bold=true"
                 alt="{{ Auth::user()->codename }} avatar"
                 class="w-8 h-8 rounded-full border-2 border-border-color flex-shrink-0 group-hover:border-primary transition-colors">
        </a>
    </div>
</header>