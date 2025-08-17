<x-app-layout theme="terminal">
    <x-slot:title>
        Dossier: {{ $user->codename }}
    </x-slot:title>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-primary text-glow">> [ AGENT DOSSIER: {{ $user->codename }} ]</h2>
        <a href="{{ route('agents.index') }}" class="text-secondary hover:text-primary transition-colors text-sm text-glow">&lt; Back to Directory</a>
    </div>
    
    <div class="bg-surface/50 border border-border-color rounded-lg p-6 space-y-4 text-glow">
        <p class="text-red-500/80 text-xs">// READ-ONLY // FOR SITUATIONAL AWARENESS ONLY</p>
        <p><span class="text-primary">> CODENAME:</span> {{ $user->codename }}</p>
        <p><span class="text-primary">> DESIGNATION:</span> {{ $user->role->alias }}</p>
        <p><span class="text-primary">> HANDLER:</span> {{ $user->parent->codename ?? '[ UNKNOWN ]' }}</p>
        <p><span class="text-primary">> AGENT SINCE:</span> {{ $user->created_at->format('Y-m-d') }}</p>
    </div>

    {{-- TIDAK ADA TOMBOL EDIT DI SINI --}}
</x-app-layout>