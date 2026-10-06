@php
    // Tombol "<-- Back" dan submit memakai tujuan yang sama dengan form edit,
    // mengikuti setelan "After Adding an Archive". URL dibangun ulang lewat
    // ArchiveReturnUrl supaya karakter '&' tidak rusak jadi '&amp;'.
    $backUrl = $returnUrl
        ? \App\Support\ArchiveReturnUrl::forController($returnUrl)
        : route('archives.index');
@endphp

<x-app-layout title="Add New Archive">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h1 class="text-lg sm:text-2xl font-bold text-primary">[ ADD_NEW_ENTRY ]</h1>
            <x-button variant="outline" href="{{ $backUrl }}">
                &lt;-- Back to Vault
            </x-button>
        </div>

        {{-- Panggil Partial Form --}}
        @include('archives._form', [
            'archive' => null,
            'categories' => $categories,
            'returnUrl' => $returnUrl ?? null,
        ])
    </div>
</x-app-layout>