<aside class="h-full bg-surface border-r bg-black border-border-color flex flex-col transition-transform duration-300 ease-in-out
           fixed inset-y-0 left-0 -translate-x-full md:relative md:translate-x-0 z-20 w-64"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
    <div
        class="relative p-4 text-2xl font-bold border-b border-border-color text-primary tracking-[.25em] flex-shrink-0 flex justify-between items-center">
        <span>[B.F]</span>

        {{-- Tombol Close Baru untuk Mobile --}}
        <button @click="sidebarOpen = false" class="md:hidden text-secondary hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-primary">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('dashboard') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Dashboard') }}</span>
        </a>

        @if(strtolower(Auth::user()->role->name) === 'director' || strtolower(Auth::user()->role->name) === 'technician')
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('admin.dashboard') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Command Center') }}</span>
        </a>
        @endif

        <a href="{{ route('friends.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('friends.index') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Friends Network') }}</span>
        </a>

        <a href="{{ route('central-tree') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('central-tree') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Central Tree') }}</span>
        </a>

        <a href="{{ route('agents.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('agents.*') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Agents Directory') }}</span>
        </a>

        <a href="{{ route('prototypes.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('prototypes.*') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Prototypes Projects') }}</span>
        </a>

        <a href="{{ route('entities.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
            {{ request()->routeIs('entities.*') 
        ? 'bg-surface-light text-primary border-l-4 border-primary' 
        : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Entities Database') }}</span>
        </a>

        <a href="{{ route('encrypted-contacts.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
            {{ request()->routeIs('encrypted-contacts.*') 
        ? 'bg-surface-light text-primary border-l-4 border-primary' 
        : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Kontak Terenkripsi') }}</span>
        </a>

        <a href="{{ route('archives.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
         {{ request()->routeIs('archives.*') 
             ? 'bg-surface-light text-primary border-l-4 border-primary' 
             : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Archives Vault') }}</span>
        </a>

        <a href="{{ route('favorites.archives') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
         {{ request()->routeIs('favorites.archives') 
             ? 'bg-surface-light text-primary border-l-4 border-primary' 
             : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Archives Favorites') }}</span>
        </a>

        <a href="{{ route('codex.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
            {{ request()->routeIs('codex.index') 
        ? 'bg-surface-light text-primary border-l-4 border-primary' 
        : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Codex') }}</span>
        </a>

        {{-- Tampilkan menu ini HANYA jika role user adalah Director dan Technician --}}
        @if(strtolower(Auth::user()->role->name) === 'director' || strtolower(Auth::user()->role->name) ===
        'technician')
        <a href="{{ route('register.agent') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
        {{ request()->routeIs('register.agent') 
            ? 'bg-surface-light text-primary border-l-4 border-primary' 
            : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Register Agent') }}</span>
        </a>
        @endif

        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('logs.*') 
                ? 'bg-surface-light border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50 text-gray-700' }}">
            <span>> {{ __('Activity Logs') }}</span>
        </a>

        <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
        {{ request()->routeIs('settings.index') 
            ? 'bg-surface-light text-primary border-l-4 border-primary' 
            : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> {{ __('Settings') }}</span>
        </a>
    </nav>

    <div class="p-4 border-t border-border-color flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left flex items-center space-x-3 px-3 py-2 rounded transition-colors hover:bg-red-900/50 hover:text-red-400 cursor-pointer">
                <span>> {{ __('Terminate Session') }}</span>
            </button>
        </form>
    </div>
</aside>