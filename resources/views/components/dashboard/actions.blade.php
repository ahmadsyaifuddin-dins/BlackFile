<div class="mb-6 font-mono">
    <h2 class="text-lg font-bold text-primary mb-3">> DIRECT ACTIONS</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('entities.create') }}" class="text-center bg-surface border-2 border-border-color border-gray-600 p-4 hover:border-primary hover:text-primary transition-colors">
            <p class="font-bold text-primary text-lg">[+]</p>
            <p class="text-xs text-secondary mt-1">REGISTER ENTITY</p>
        </a>
        <a href="{{ route('prototypes.create') }}" class="text-center bg-surface border-2 border-border-color border-gray-600 p-4 hover:border-primary hover:text-primary transition-colors">
            <p class="font-bold text-primary text-lg">[+]</p>
            <p class="text-xs text-secondary mt-1">INITIATE PROJECT</p>
        </a>
        <a href="{{ route('central-tree') }}" class="text-center bg-surface border-2 border-border-color border-gray-600 p-4 hover:border-primary hover:text-primary transition-colors">
            <p class="font-bold text-primary text-lg">[O]</p>
            <p class="text-xs text-secondary mt-1">VIEW CENTRAL TREE</p>
        </a>
        @if(strtolower(Auth::user()->role->name) === 'director' || strtolower(Auth::user()->role->name) === 'technician')
        <a href="{{ route('register.agent') }}" class="text-center bg-surface border-2 border-border-color border-gray-600 p-4 hover:border-primary hover:text-primary transition-colors">
            <p class="font-bold text-primary text-lg">[+]</p>
            <p class="text-xs text-secondary mt-1">REGISTER AGENT</p>
        </a>
        @endif
    </div>
</div>