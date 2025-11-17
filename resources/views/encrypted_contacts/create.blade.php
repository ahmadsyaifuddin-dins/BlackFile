<x-app-layout title="Add New Encrypted Contact">
    {{-- Header --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h1 class="text-2xl font-bold text-primary tracking-widest font-mono text-glow">
                > ADD NEW ENCRYPTED CONTACT
            </h1>
            <x-button variant="outline" href="{{ route('encrypted-contacts.index') }}">
                &lt; CANCEL & RETURN TO VAULT
            </x-button>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="bg-surface border border-border-color p-6 font-mono">
        {{-- Panggil Partial Form (contact = null, payload = kosong) --}}
        @include('encrypted_contacts._form', ['contact' => null, 'payload' => []])
    </div>
</x-app-layout>