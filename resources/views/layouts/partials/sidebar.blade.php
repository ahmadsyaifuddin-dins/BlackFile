<aside
    class="h-full bg-black border-r border-primary/20 flex flex-col transition-transform duration-300 ease-in-out
           fixed inset-y-0 left-0 -translate-x-full md:relative md:translate-x-0 z-30 w-70 shadow-2xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

    {{-- HEADER SECTION --}}
    {{-- Padding dikurangi jadi p-6 agar tidak terlalu boros tempat --}}
    <div class="relative p-3 border-b border-primary/20 flex-shrink-0 bg-black/50">
        {{-- Decorative Corners (Tech Feel) --}}
        <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-primary/50"></div>
        <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-primary/50"></div>
        <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-primary/50"></div>
        <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-primary/50"></div>

        <!-- Logo & Identity -->
        <div class="flex flex-col items-center justify-center gap-2">
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-primary/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                {{-- UKURAN LOGO DIKECILKAN (w-14 = 56px) --}}
                <img src="{{ asset('app-icon.png') }}" alt="Logo"
                    class="w-14 h-14 relative z-10 drop-shadow-[0_0_10px_rgba(46,160,67,0.5)]">
            </div>
            <div class="text-center mt-1">
                {{-- UKURAN JUDUL DIKECILKAN (text-xl) --}}
                <h2 class="text-xl font-bold text-primary tracking-[.25em] font-mono leading-none">BLACKFILE</h2>
                <span class="text-[10px] text-secondary uppercase tracking-widest opacity-70">System v5.0.0</span>
            </div>
        </div>

        <!-- Tombol Close (Mobile Only) -->
        <button @click="sidebarOpen = false"
            class="md:hidden absolute right-4 top-4 text-secondary hover:text-red-500 focus:outline-none transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{-- NAVIGATION SCROLLABLE --}}
    {{-- Padding vertikal item menu juga sedikit disesuaikan --}}
    <nav
        class="flex-1 px-4 py-4 space-y-2 overflow-y-auto overflow-x-hidden font-mono text-base scrollbar-thin scrollbar-thumb-primary/20 scrollbar-track-transparent">

        {{-- Helper Variable untuk Class Active/Inactive --}}
        @php
            $activeClass =
                'bg-[var(--color-primary)]/10 text-primary border-l-4 border-primary shadow-[inset_15px_0_15px_-10px_rgba(46,160,67,0.2)] translate-x-1 font-bold';
            $inactiveClass =
                'border-l-4 border-transparent text-secondary hover:text-white hover:bg-white/5 hover:border-white/20';
            $baseLinkClass =
                'group flex items-center space-x-4 px-4 py-3 transition-all duration-200 ease-out mb-1 rounded-r-md';
        @endphp

        <a href="{{ route('dashboard') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Dashboard') }}</span>
        </a>

        @if (strtolower(Auth::user()->role->name) === 'director' || strtolower(Auth::user()->role->name) === 'technician')
            <a href="{{ route('admin.dashboard') }}"
                class="{{ $baseLinkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
                <span>{{ __('Command Center') }}</span>
            </a>
        @endif

        <div class="my-4 border-t border-primary/10 mx-2"></div> {{-- Separator --}}
        <div class="px-4 text-xs text-primary/50 uppercase tracking-widest font-bold mb-2">Network & Assets</div>

        <a href="{{ route('friends.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('friends.index') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Friends Network') }}</span>
        </a>

        <a href="{{ route('central-tree') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('central-tree') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Central Tree') }}</span>
        </a>

        <a href="{{ route('agents.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('agents.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Agents Directory') }}</span>
        </a>

        <a href="{{ route('prototypes.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('prototypes.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Prototypes Projects') }}</span>
        </a>

        <div class="my-4 border-t border-primary/10 mx-2"></div>
        <div class="px-4 text-xs text-primary/50 uppercase tracking-widest font-bold mb-2">Operations</div>

        <a href="{{ route('dark-archives.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('dark-archives.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Dark Archives') }}</span>
        </a>

        <a href="{{ route('tools.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('tools.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('OSINT Arsenal') }}</span>
        </a>

        <a href="{{ route('entities.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('entities.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Entities Database') }}</span>
        </a>

        <a href="{{ route('battle.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('battle.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Conflict Simulator') }}</span>
        </a>

        <a href="{{ route('encrypted-contacts.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('encrypted-contacts.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Encrypted Contacts') }}</span>
        </a>

        {{-- DROPDOWN: ARCHIVES --}}
        <div x-data="{ open: {{ request()->routeIs('archives.*') || request()->routeIs('favorites.archives') ? 'true' : 'false' }} }" class="mb-1">
            <button @click="open = !open"
                class="w-full flex items-center justify-between {{ $baseLinkClass }} {{ request()->routeIs('archives.*') || request()->routeIs('favorites.archives') ? 'text-primary' : 'text-secondary hover:text-white' }}">
                <div class="flex items-center space-x-4">
                    <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
                    <span>{{ __('Archives') }}</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform duration-200 text-secondary"
                    :class="{ 'rotate-90 text-primary': open }" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div x-show="open" x-collapse
                class="pl-10 pr-2 space-y-1 bg-black/30 py-2 border-l-2 border-primary/10 ml-6 mb-1">
                <a href="{{ route('archives.index') }}"
                    class="block px-4 py-2 rounded text-sm transition-all {{ request()->routeIs('archives.index') ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:text-white hover:bg-white/5' }}">
                    // {{ __('All Files') }}
                </a>
                <a href="{{ route('favorites.archives') }}"
                    class="block px-4 py-2 rounded text-sm transition-all {{ request()->routeIs('favorites.archives') ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:text-white hover:bg-white/5' }}">
                    // {{ __('Favorites') }}
                </a>
            </div>
        </div>

        {{-- DROPDOWN: EPILOGUE (CREDITS) --}}
        <div x-data="{ open: {{ request()->routeIs('credits.*') ? 'true' : 'false' }} }" class="mb-1">
            <button @click="open = !open"
                class="w-full flex items-center justify-between {{ $baseLinkClass }} {{ request()->routeIs('credits.*') ? 'text-primary' : 'text-secondary hover:text-white' }}">
                <div class="flex items-center space-x-4">
                    <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
                    <span>{{ __('Epilogue') }}</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform duration-200 text-secondary"
                    :class="{ 'rotate-90 text-primary': open }" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div x-show="open" x-collapse
                class="pl-10 pr-2 space-y-1 bg-black/30 py-2 border-l-2 border-primary/10 ml-6 mb-1">
                <a href="{{ route('credits.index') }}"
                    class="block px-4 py-2 rounded text-sm transition-all {{ request()->routeIs('credits.index') ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:text-white hover:bg-white/5' }}">
                    // Manage Credits
                </a>

                @if (strtolower(Auth::user()->role->name) === 'director')
                    <a href="{{ route('credits.viewLog') }}"
                        class="block px-4 py-2 rounded text-sm transition-all {{ request()->routeIs('credits.viewLog') ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:text-white hover:bg-white/5' }}">
                        // Access Log
                    </a>
                    <a href="{{ route('credits.default-music.index') }}"
                        class="block px-4 py-2 rounded text-sm transition-all {{ request()->routeIs('credits.default-music.index') ? 'text-primary font-bold bg-primary/5' : 'text-secondary hover:text-white hover:bg-white/5' }}">
                        // Audio Config
                    </a>
                @endif
            </div>
        </div>

        @if (strtolower(Auth::user()->role->name) === 'director' || strtolower(Auth::user()->role->name) === 'technician')
            <a href="{{ route('register.agent') }}"
                class="{{ $baseLinkClass }} {{ request()->routeIs('register.agent') ? $activeClass : $inactiveClass }}">
                <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
                <span>{{ __('Register Agent') }}</span>
            </a>
        @endif

        <div class="my-4 border-t border-primary/10 mx-2"></div>
        <div class="px-4 text-xs text-primary/50 uppercase tracking-widest font-bold mb-2">System</div>

        <a href="{{ route('codex.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('codex.index') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Codex') }}</span>
        </a>

        <a href="#"
            class="{{ $baseLinkClass }} {{ request()->routeIs('logs.*') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Activity Logs') }}</span>
        </a>

        <a href="{{ route('settings.index') }}"
            class="{{ $baseLinkClass }} {{ request()->routeIs('settings.index') ? $activeClass : $inactiveClass }}">
            <span class="opacity-50 group-hover:text-primary transition-colors text-lg">></span>
            <span>{{ __('Settings') }}</span>
        </a>

    </nav>

    {{-- FOOTER SECTION (LOGOUT) --}}
    <div class="p-2 border-t border-primary/20 bg-black/80">
        <form method="POST" action="{{ route('logout') }}" x-data="{
            async requestLogout() {
                const confirmed = await window.agentConfirm(
                    'TERMINATE SESSION?',
                    'Confirm disconnection sequence. Secure channel will be closed.',
                    'DISCONNECT',
                    'CANCEL'
                );
        
                if (confirmed) {
                    this.$el.submit();
                }
            }
        }"
            @submit.prevent="requestLogout">
            @csrf
            <button type="submit"
                class="cursor-pointer w-full group relative flex items-center justify-center space-x-3 px-6 py-2 rounded border-2 border-red-900/30 text-red-500/80 hover:text-black hover:bg-red-600 hover:border-red-600 hover:shadow-[0_0_20px_rgba(220,38,38,0.5)] transition-all duration-300 overflow-hidden font-bold tracking-[0.2em] text-sm uppercase">
                <i class="fa-solid fa-power-off text-base"></i>
                <span>TERMINATE</span>
            </button>
        </form>
        <div class="mt-3 text-center">
            <span class="text-[10px] text-secondary/30 font-mono tracking-wider">SECURE CONNECTION ESTABLISHED</span>
        </div>
    </div>
</aside>
