<aside
    class="w-64 bg-surface border-r bg-black border-border-color flex flex-col transition-transform duration-300 ease-in-out z-20
           fixed inset-y-0 left-0 md:relative md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="p-4 text-2xl font-bold border-b border-border-color text-primary tracking-[.25em]">
        [B.F]
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-green-700">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('dashboard') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> Dashboard</span>
        </a>

        <a href="{{ route('friends.index') }}" 
           class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('friends.index') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> Friends Network</span>
        </a>

        <a href="{{ route('friends.central-tree') }}" 
           class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('friends.central-tree') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> Central Tree</span>
        </a>

        <a href="#" 
           class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors
           {{ request()->routeIs('logs.*') 
                ? 'bg-surface-light text-primary border-l-4 border-primary' 
                : 'border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50' }}">
            <span>> Activity Logs</span>
        </a>

        {{-- Item tambahan --}}
        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50"><span>> Laporan Mingguan</span></a>
        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50"><span>> Arsip Intelijen</span></a>
        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50"><span>> Pengaturan Agen</span></a>
        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-r-md transition-colors border-l-4 border-transparent hover:bg-surface-light hover:border-primary/50"><span>> Kontak Terenkripsi</span></a>
    </nav>

    <div class="p-4 border-t border-border-color">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="w-full text-left flex items-center space-x-3 px-3 py-2 rounded transition-colors hover:bg-red-900/50 hover:text-red-400 cursor-pointer">
                <span>> Terminate Session</span>
            </button>
        </form>
    </div>
</aside>
