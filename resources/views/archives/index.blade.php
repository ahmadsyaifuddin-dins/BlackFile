<x-app-layout title="Archives Vault">
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">[ ARCHIVES_VAULT ]</h1>
            <x-button variant="outline" href="{{ route('archives.create') }}">
                + ADD_NEW_ENTRY
            </x-button>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
        <div class="px-4 py-3 border rounded-md bg-surface-light border-primary text-primary">
            <span class="font-bold">> Status:</span> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="px-4 py-3 border rounded-md bg-surface-light border-red-500 text-red-500">
            <span class="font-bold">> Status:</span> {{ session('error') }}
        </div>
        @endif
        
        {{-- Panggil Komponen Filter --}}
        @include('archives.partials._filter-form', ['searchRoute' => route('archives.index')])

        {{-- Panggil Komponen Tabel untuk Desktop --}}
        <div class="hidden md:block">
            @include('archives.partials._table-view', ['archives' => $archives])
        </div>

        {{-- Panggil Komponen Kartu untuk Mobile --}}
        <div class="md:hidden">
            @include('archives.partials._card-view', ['archives' => $archives])
        </div>
        
        {{-- Paginasi --}}
        @if ($archives->hasPages())
            <div class="p-4 bg-surface border-t border-border rounded-md">
                {{ $archives->links() }}
            </div>
        @endif
    </div>
</x-app-layout>