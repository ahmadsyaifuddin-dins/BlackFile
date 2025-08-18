<x-app-layout theme="terminal">
    <x-slot:title>
        My Dossier
    </x-slot:title>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-primary text-glow">> [ PERSONAL DOSSIER ]</h2>
    </div>

    <div class="bg-surface/50 border border-border-color rounded-lg p-6 text-glow flex flex-col sm:flex-row items-start gap-6">
        
        <!-- Avatar -->
        <div class="flex-shrink-0 mx-auto sm:mx-0">
            <img src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->codename).'&size=128&background=041607&color=00ff88&bold=true' }}" 
                 alt="Avatar" class="w-32 h-32 rounded-full border-4 border-border-color shadow-lg">
        </div>
        
        <!-- Detail Teks -->
        <div class="space-y-4 w-full">
            <p><span class="text-primary">> REAL NAME:</span> {{ $user->name }}</p>
            <p><span class="text-primary">> CODENAME:</span> {{ $user->codename }}</p>
            <p><span class="text-primary">> DESIGNATION:</span> {{ $user->role->alias }}</p>
            <p class="border-t border-border-color/50 pt-4 mt-4"><span class="text-primary">> LOGIN ID:</span> {{ $user->username }}</p>
            <p><span class="text-primary">> HANDLER:</span> {{ $user->parent->codename ?? '[ DIRECTORATE ]' }}</p>
            <p><span class="text-primary">> SPECIALIZATION:</span> {{ $user->specialization ?? 'N/A' }}</p>
            <p><span class="text-primary">> QUOTES:</span> "{{ $user->quotes ?? '...' }}"</p>
            <p><span class="text-primary">> LAST ACTIVITY:</span> {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Never' }}</p>
            <p><span class="text-primary">> RECOVERY EMAIL:</span> {{ $user->email }}</p>
            <p><span class="text-primary">> REGISTERED ON:</span> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <div class="mt-6 border-t border-border-color pt-6 flex justify-end">
        <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-primary text-base hover:bg-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
            [ EDIT PROFILE ]
        </a>
    </div>
</x-app-layout>