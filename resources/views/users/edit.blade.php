<x-app-layout>
    <x-slot:title>
        Edit Agent: {{ $user->codename }}
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-primary uppercase">
                    > [ EDITING AGENT: {{ $user->codename }} ]
                </h2>
                <p class="text-sm text-secondary font-mono mt-1">Updating existing personnel dossier.</p>
            </div>
            <x-button variant="outline" href="{{ url()->previous() }}">
                &lt; Back
            </x-button>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg font-mono text-sm" role="alert">
            <p class="font-bold mb-2">> Update Failed:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Panggil Partial Form (Mode Edit: user=$user) --}}
        @include('users._form', ['user' => $user, 'roles' => $roles])
        
    </div>
</x-app-layout>