<x-app-layout theme="terminal">
    <x-slot:title>
        Agent: {{ $user->codename }}
    </x-slot:title>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary text-glow"> > [ AGENT : {{ $user->codename }} ] </h2>
        <div class="mt-3 sm:mt-2 flex sm:justify-end">
            <a href="{{ route('agents.index') }}"
                class="text-secondary hover:text-primary transition-colors text-sm text-glow">&lt; Back to Directory</a>
        </div>
    </div>
    
    {{-- Agent sekarang memiliki layout flex untuk menampung avatar --}}
    <div class="bg-surface/50 border border-border-color rounded-lg p-6 text-glow flex flex-col sm:flex-row items-start gap-6">
        
        <div class="flex-shrink-0 mx-auto sm:mx-0">
            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://blackfile.xo.je/agent-default.jpg' }}" 
                 alt="Avatar" class="w-32 h-32 object-cover rounded-full border-4 border-border-color shadow-lg">
        </div>
        
        <div class="space-y-4 w-full">
            <p class="text-red-500/80 text-xs">// READ-ONLY // FOR SITUATIONAL AWARENESS ONLY</p>
            <p><span class="text-primary/25">> REAL NAME:</span> {{ $user->name }}</p>
            <p><span class="text-primary/25">> CODENAME:</span> {{ $user->codename }}</p>
            <p><span class="text-primary/25">> DESIGNATION:</span> {{ $user->role->alias }}</p>
            <p><span class="text-primary/25">> SPECIALIZATION:</span> {{ $user->specialization ?? 'N/A' }}</p>
            <p><span class="text-primary/25">> QUOTES:</span> "{{ $user->quotes ?? '...' }}"</p>
            <p><span class="text-primary/25">> HANDLER:</span> {{ $user->parent->codename ?? '[ UNKNOWN ]' }}</p>
            <p><span class="text-primary/25">> LAST ACTIVITY:</span> {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}</p>
            <p><span class="text-primary/25">> AGENT SINCE:</span> {{ $user->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="mt-6 bg-surface/50 border border-border-color rounded-lg p-6 text-glow">
        <h3 class="text-xl font-bold text-primary text-glow mb-4">> [ AGENT PREFERENCES ]</h3>
        
        @if ($user->settings)
            <div class="space-y-4">
                <p><span class="text-primary/25">> LANGUAGE:</span> {{ $localeName }}</p>
                <p><span class="text-primary/25">> DATA PER PAGE:</span> {{ $perPageName }}</p>
                <p><span class="text-primary/25">> TERMINAL THEME:</span> {{ $themeName }}</p>
            </div>
        @else
            <p class="text-green-600/80 text-xs">// NO CUSTOM PREFERENCES SET. USING SYSTEM DEFAULTS.</p>
        @endif
    </div>

</x-app-layout>