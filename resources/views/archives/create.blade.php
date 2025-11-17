<x-app-layout title="Add New Archive">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-lg sm:text-2xl font-bold text-primary">[ ADD_NEW_ENTRY ]</h1>
            <x-button variant="outline" href="{{ route('archives.index') }}">
                &lt;-- Back to Vault
            </x-button>
        </div>

        {{-- Panggil Partial Form --}}
        @include('archives._form', ['archive' => null, 'categories' => $categories])
    </div>
</x-app-layout>