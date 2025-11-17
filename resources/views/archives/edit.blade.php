@php
    // Logika 'Back' yang pintar untuk kembali ke halaman detail atau index
    $backUrl = request('return_url') ?? route('archives.show', $archive);
@endphp

<x-app-layout title="Edit Archive: {{ $archive->name }}">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ EDIT_ENTRY ]</h1>
            <x-button href="{{ $backUrl }}" variant="outline">
                &lt;-- Back to Vault
            </x-button>
        </div>

        {{-- Panggil Partial Form --}}
        @include('archives._form', ['archive' => $archive, 'categories' => $categories])
    </div>
</x-app-layout>