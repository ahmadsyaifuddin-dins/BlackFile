<x-app-layout :title="'View Contact: ' . $contact->codename">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-primary tracking-widest font-mono">
                    > VIEWING ENCRYPTED FILE
                </h1>
                <p class="text-sm text-secondary font-mono mt-1">Codename: <span class="text-white">{{ $contact->codename }}</span></p>
            </div>
            <a href="{{ route('encrypted-contacts.index') }}" class="w-full sm:w-auto text-center bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 hover:text-primary hover:border-primary transition-colors">
                &lt; RETURN TO VAULT
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-900/50 border border-red-500/50 text-red-400 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Cek apakah data sudah didekripsi --}}
    @if($isDecrypted)
        {{-- TAMPILAN DATA TERDEKRIPSI --}}
        <div class="bg-surface border border-border-color p-6 font-mono space-y-6">
            <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> DECRYPTED DATA PAYLOAD</h2>

            @php
                // Mengambil payload yang sudah didekripsi oleh model
                $payload = $contact->encrypted_payload;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div><span class="text-secondary">REAL NAME:</span> <span class="text-white font-bold">{{ $payload['real_name'] ?? 'N/A' }}</span></div>
                <div><span class="text-secondary">GENDER:</span> <span class="text-white font-bold">{{ $payload['gender'] ?? 'N/A' }}</span></div>
                <div><span class="text-secondary">PLACE OF BIRTH:</span> <span class="text-white font-bold">{{ $payload['pob'] ?? 'N/A' }}</span></div>
                <div><span class="text-secondary">DATE OF BIRTH:</span> <span class="text-white font-bold">{{ $payload['dob'] ?? 'N/A' }}</span></div>
                <div><span class="text-secondary">PHONE/WA:</span> <span class="text-white font-bold">{{ $payload['phone'] ?? 'N/A' }}</span></div>
                <div><span class="text-secondary">EMAIL:</span> <span class="text-white font-bold">{{ $payload['email'] ?? 'N/A' }}</span></div>
            </div>
            <div>
                <p class="text-secondary">ADDRESS:</p>
                <p class="text-white font-bold whitespace-pre-wrap">{{ $payload['address'] ?? 'N/A' }}</p>
            </div>
            <div class="pt-4 border-t border-dashed border-border-color">
                <h3 class="text-md font-bold text-yellow-400">> ACADEMIC INFORMATION SYSTEM (SIA)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 text-sm">
                    <div><span class="text-secondary">NPM / STUDENT ID:</span> <span class="text-white font-bold">{{ $payload['npm'] ?? 'N/A' }}</span></div>
                    <div><span class="text-secondary">SIA PASSWORD:</span> <span class="text-white font-bold">{{ $payload['sia_password'] ?? 'N/A' }}</span></div>
                </div>
            </div>
        </div>
    @else
        {{-- TAMPILAN FORM MASTER PASSWORD --}}
        <div class="max-w-xl mx-auto bg-surface border-2 border-yellow-500/50 p-6 font-mono">
            <h2 class="text-lg font-bold text-yellow-400 border-b border-yellow-500/30 pb-2 mb-4">> VAULT ACCESS REQUIRED</h2>
            <p class="text-sm text-secondary mb-4">Enter your Master Password to decrypt and view the data for this contact.</p>

            <form action="{{ route('encrypted-contacts.unlock', $contact) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="master_password" class="block text-sm text-secondary mb-2">Master Password:</label>
                    <input type="password" name="master_password" id="master_password" required autofocus
                           class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary text-primary font-bold py-2 px-6 hover:bg-primary-hover transition-colors cursor-pointer">
                        DECRYPT
                    </button>
                </div>
            </form>
        </div>
    @endif
</x-app-layout>
