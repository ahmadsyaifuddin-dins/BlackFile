<x-app-layout theme="terminal">
    <x-slot:title>
        My Dossier
    </x-slot:title>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-primary text-glow">> [ PERSONAL DOSSIER ]</h2>
    </div>

    <div class="bg-surface/50 border border-border-color rounded-lg p-6 space-y-4 text-glow">
        <p><span class="text-primary">> CODENAME:</span> {{ $user->codename }}</p>
        <p><span class="text-primary">> REAL NAME:</span> {{ $user->name }}</p>
        <p><span class="text-primary">> DESIGNATION:</span> {{ $user->role->alias }}</p>
        <p class="border-t border-border-color/50 pt-4 mt-4"><span class="text-primary">> LOGIN ID:</span> {{ $user->username }}</p>
        <p><span class="text-primary">> RECOVERY EMAIL:</span> {{ $user->email }}</p>
        <p><span class="text-primary">> HANDLER:</span> {{ $user->parent->codename ?? '[ DIRECTORATE ]' }}</p>
        <p><span class="text-primary">> REGISTERED ON:</span> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="mt-6 border-t border-border-color pt-6 flex justify-end">
        <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-primary text-base hover:bg-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
            [ EDIT PROFILE ]
        </a>
    </div>
</x-app-layout>