<x-app-layout :title="'Edit Contact: ' . $contact->codename">
    {{-- Header --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-lg md:text-xl font-bold text-primary tracking-widest font-mono">
                    > EDIT ENCRYPTED FILE
                </h1>
                <p class="text-sm text-secondary font-mono mt-1">Codename: <span class="text-white">{{ $contact->codename }}</span></p>
            </div>
            <x-button href="{{ route('encrypted-contacts.show', $contact) }}">
                &lt; CANCEL & VIEW FILE
            </x-button>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-900/50 border border-red-500/50 text-red-400 px-4 py-3 rounded relative mb-6 font-mono text-sm" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Cek apakah data sudah didekripsi untuk diedit --}}
    @if($isDecrypted)
        {{-- 
            TAMPILAN FORM EDIT 
            Menggunakan partial form dengan data contact dan payload yang sudah didekripsi.
        --}}
        <div class="bg-surface border border-border-color p-6 font-mono">
             @include('encrypted_contacts._form', [
                 'contact' => $contact, 
                 'payload' => $contact->encrypted_payload // Asumsi accessor/metode ini mengembalikan array
             ])
        </div>
    @else
        {{-- 
            TAMPILAN FORM MASTER PASSWORD (UNLOCK)
            Bagian ini tetap manual karena unik untuk halaman Edit.
        --}}
        <div class="max-w-xl mx-auto bg-surface border-2 border-yellow-500/50 p-6 font-mono">
            <h2 class="text-lg font-bold text-yellow-400 border-b border-yellow-500/30 pb-2 mb-4">> AUTHORIZATION REQUIRED</h2>
            <p class="text-sm text-secondary mb-4">Enter your Master Password to decrypt and edit the data for this contact.</p>
            
            <form action="{{ route('encrypted-contacts.unlock', ['encryptedContact' => $contact, 'redirect' => 'edit']) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="master_password" class="block text-sm text-secondary mb-2">Master Password:</label>
                    <x-forms.input type="password" name="master_password" id="master_password" required autofocus />
                </div>
                <div class="flex justify-end pt-2">
                    <x-button type="submit">
                        AUTHORIZE & EDIT
                    </x-button>
                </div>
            </form>
        </div>
    @endif
</x-app-layout>