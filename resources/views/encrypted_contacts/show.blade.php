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
        {{-- [PERUBAHAN] TAMPILAN DATA TERDEKRIPSI DENGAN TOMBOL HIDE/UNHIDE --}}
        <div x-data="{ unhidden: true }" class="bg-surface border border-border-color p-6 font-mono space-y-6">
            <div class="flex justify-between items-center border-b border-border-color pb-2 mb-4">
                <h2 class="text-lg font-bold text-primary">> DECRYPTED DATA PAYLOAD</h2>
                {{-- Tombol Hide/Unhide --}}
                <button @click="unhidden = !unhidden" class="border border-border-color text-secondary px-3 py-1 text-xs hover:border-primary hover:text-primary">
                    <span x-show="unhidden">HIDE DATA</span>
                    <span x-show="!unhidden" style="display: none;">UNHIDE DATA</span>
                </button>
            </div>

            @php
                $payload = $contact->encrypted_payload;
            @endphp

            {{-- Tampilan data sekarang menggunakan x-show untuk beralih --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <span class="text-secondary">REAL NAME:</span>
                    <span x-show="unhidden" class="text-white font-bold">{{ $payload['real_name'] ?? 'N/A' }}</span>
                    <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                </div>
                <div>
                    <span class="text-secondary">GENDER:</span>
                    <span x-show="unhidden" class="text-white font-bold">{{ $payload['gender'] ?? 'N/A' }}</span>
                    <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                </div>
                <div>
                    <span class="text-secondary">PLACE OF BIRTH:</span>
                    <span x-show="unhidden" class="text-white font-bold">{{ $payload['pob'] ?? 'N/A' }}</span>
                    <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                </div>
                <div>
                    <span class="text-secondary">DATE OF BIRTH:</span>
                    <span x-show="unhidden" class="text-white font-bold">{{ $payload['dob'] ?? 'N/A' }}</span>
                    <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                </div>
                <div>
                    <span class="text-secondary">PHONE/WA:</span>
                    <span x-show="unhidden" class="text-white font-bold">{{ $payload['phone'] ?? 'N/A' }}</span>
                    <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                </div>
                <div>
                    <span class="text-secondary">EMAIL:</span>
                    <span x-show="unhidden" class="text-white font-bold">{{ $payload['email'] ?? 'N/A' }}</span>
                    <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                </div>
            </div>
            <div>
                <p class="text-secondary">ADDRESS:</p>
                <p x-show="unhidden" class="text-white font-bold whitespace-pre-wrap">{{ $payload['address'] ?? 'N/A' }}</p>
                <p x-show="!unhidden" class="text-gray-500 font-mono">**********</p>
            </div>
            <div class="pt-4 border-t border-dashed border-border-color">
                <h3 class="text-md font-bold text-yellow-400">> ACADEMIC INFORMATION SYSTEM (SIA)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4 text-sm">
                    <div>
                        <span class="text-secondary">NPM / STUDENT ID:</span>
                        <span x-show="unhidden" class="text-white font-bold">{{ $payload['npm'] ?? 'N/A' }}</span>
                        <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                    </div>
                    <div>
                        <span class="text-secondary">SIA PASSWORD:</span>
                        <span x-show="unhidden" class="text-white font-bold">{{ $payload['sia_password'] ?? 'N/A' }}</span>
                        <span x-show="!unhidden" class="text-gray-500 font-mono">**********</span>
                    </div>
                </div>
            </div>
            
            {{-- Tampilan data terenkripsi mentah --}}
            <div x-show="!unhidden" class="pt-4 border-t border-border-color" style="display: none;">
                <p class="text-secondary text-xs">RAW ENCRYPTED DATA SNIPPET:</p>
                <p class="text-gray-600 font-mono text-xs break-all mt-1">{{ $encryptedSnippet }}</p>
            </div>
        </div>
    @else
        {{-- TAMPILAN FORM MASTER PASSWORD (tidak berubah) --}}
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
                    <button type="submit" class="bg-primary text-black font-bold py-2 px-6 hover:bg-primary-hover transition-colors">
                        DECRYPT
                    </button>
                </div>
            </form>
        </div>
    @endif
</x-app-layout>
