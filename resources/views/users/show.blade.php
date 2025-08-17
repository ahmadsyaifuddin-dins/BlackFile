<x-app-layout theme="terminal">
    <x-slot:title>
        Dossier: {{ $user->codename }}
    </x-slot:title>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-primary text-glow"> > [ AGENT : {{ $user->codename }} ] </h2>
        <div class="mt-3 sm:mt-2 flex sm:justify-end">
            <a href="{{ route('agents.index') }}"
                class="text-secondary hover:text-primary transition-colors text-sm text-glow">&lt; Back to Directory</a>
        </div>
    </div>
    
    {{-- [DIUBAH] Dossier sekarang memiliki layout flex untuk menampung avatar --}}
    <div class="bg-surface/50 border border-border-color rounded-lg p-6 text-glow flex flex-col sm:flex-row items-start gap-6">
        
        <!-- Avatar -->
        <div class="flex-shrink-0 mx-auto sm:mx-0">
            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->codename).'&size=128&background=041607&color=00ff88&bold=true' }}" 
                 alt="Avatar" class="w-32 h-32 rounded-full border-4 border-border-color shadow-lg">
        </div>
        
        <!-- Detail Teks -->
        <div class="space-y-4 w-full">
            <p class="text-red-500/80 text-xs">// READ-ONLY // FOR SITUATIONAL AWARENESS ONLY</p>
            <p><span class="text-primary">> REAL NAME:</span> {{ $user->name }}</p>
            <p><span class="text-primary">> CODENAME:</span> {{ $user->codename }}</p>
            <p><span class="text-primary">> DESIGNATION:</span> {{ $user->role->alias }}</p>
            <p><span class="text-primary">> HANDLER:</span> {{ $user->parent->codename ?? '[ UNKNOWN ]' }}</p>
            <p><span class="text-primary">> SPECIALIZATION:</span> {{ $user->specialization ?? 'N/A' }}</p>
            <p><span class="text-primary">> QUOTES:</span> "{{ $user->quotes ?? '...' }}"</p>
            <p><span class="text-primary">> LAST ACTIVITY:</span> {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}</p>
            <p><span class="text-primary">> AGENT SINCE:</span> {{ $user->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    {{-- TIDAK ADA TOMBOL EDIT DI SINI --}}
</x-app-layout>