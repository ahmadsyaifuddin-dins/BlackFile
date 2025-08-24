<x-app-layout :title="'Edit Contact: ' . $contact->codename">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-primary tracking-widest font-mono">
                    > EDIT ENCRYPTED FILE
                </h1>
                <p class="text-sm text-secondary font-mono mt-1">Codename: <span class="text-white">{{
                        $contact->codename }}</span></p>
            </div>
            <a href="{{ route('encrypted-contacts.show', $contact) }}"
                class="w-full sm:w-auto text-center bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 hover:text-primary hover:border-primary transition-colors">
                &lt; CANCEL & VIEW FILE
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-red-900/50 border border-red-500/50 text-red-400 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Cek apakah data sudah didekripsi untuk diedit --}}
    @if($isDecrypted)
    {{-- TAMPILAN FORM EDIT --}}
    <div class="bg-surface border border-border-color p-6 font-mono">
        <form action="{{ route('encrypted-contacts.update', $contact) }}" enctype="multipart/form-data" method="POST"
            class="space-y-8">
            @csrf
            @method('PUT')

            {{-- [PERBAIKAN] Variabel didefinisikan di sini, di luar form --}}
            @php
            $payload = $contact->encrypted_payload;
            $customFields = old('payload.custom_fields', $payload['custom_fields'] ?? []);
            if (!is_array($customFields)) {
            $customFields = [];
            }
            @endphp

            {{-- Data Tidak Terenkripsi --}}
            {{-- ================================================================ --}}
            {{-- == BAGIAN BARU: MANAJEMEN FOTO PROFIL == --}}
            {{-- ================================================================ --}}
            <div class="pt-6 border-t border-dashed border-border-color">
                <h2 class="text-lg font-bold text-primary">> PROFILE PHOTO</h2>
                @if($contact->profile_photo_path)
                <div class="my-4">
                    <p class="text-sm text-secondary mb-2">Current Photo:</p>
                    <img src="{{ asset($contact->profile_photo_path) }}" alt="{{ $contact->codename }}"
                        class="w-32 h-32 object-cover border-2 border-border-color">
                </div>
                @endif
                <div>
                    <label for="profile_photo" class="block text-sm text-secondary mb-2">{{ $contact->profile_photo_path
                        ? 'Replace Photo (Optional):' : 'Upload Photo (Optional):' }}</label>
                    <input type="file" name="profile_photo" id="profile_photo"
                        class="block w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-primary hover:file:bg-primary-hover cursor-pointer">
                </div>
            </div>

            <div>
                <label for="codename" class="flex-shrink-0 text-primary text-lg">> CODENAME:</label>
                <input type="text" name="codename" id="codename" required
                    value="{{ old('codename', $contact->codename) }}"
                    class="mt-2 w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
            </div>

            {{-- Data Terenkripsi --}}
            <div class="pt-6 border-t-2 border-dashed border-border-color space-y-6">
                <h2 class="text-lg font-bold text-primary">> ENCRYPTED DATA PAYLOAD</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Asli --}}
                    <div>
                        <label for="payload_real_name" class="block text-sm text-secondary mb-1">Real Name:</label>
                        <input type="text" name="payload[real_name]" id="payload_real_name"
                            value="{{ old('payload.real_name', $payload['real_name'] ?? '') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="payload_gender" class="block text-sm text-secondary mb-1">Gender:</label>
                        <select name="payload[gender]" id="payload_gender"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                            <option class="text-black" value="" @selected(empty($payload['gender']))>-- Select --
                            </option>
                            <option class="text-black" value="Male" @selected(old('payload.gender', $payload['gender']
                                ?? '' )=='Male' )>
                                Male</option>
                            <option class="text-black" value="Female" @selected(old('payload.gender', $payload['gender']
                                ?? '' )=='Female' )>Female</option>
                            <option class="text-black" value="Other" @selected(old('payload.gender', $payload['gender']
                                ?? '' )=='Other' )>
                                Other</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tempat Lahir --}}
                    <div>
                        <label for="payload_pob" class="block text-sm text-secondary mb-1">Place of Birth:</label>
                        <input type="text" name="payload[pob]" id="payload_pob"
                            value="{{ old('payload.pob', $payload['pob'] ?? '') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="payload_dob" class="block text-sm text-secondary mb-1">Date of Birth:</label>
                        <input type="date" name="payload[dob]" id="payload_dob"
                            value="{{ old('payload.dob', $payload['dob'] ?? '') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="payload_address" class="block text-sm text-secondary mb-1">Address:</label>
                    <textarea name="payload[address]" id="payload_address" rows="3"
                        class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">{{ old('payload.address', $payload['address'] ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nomor HP/WA --}}
                    <div>
                        <label for="payload_phone" class="block text-sm text-secondary mb-1">Phone / WhatsApp:</label>
                        <input type="text" name="payload[phone]" id="payload_phone"
                            value="{{ old('payload.phone', $payload['phone'] ?? '') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                    {{-- Email --}}
                    <div>
                        <label for="payload_email" class="block text-sm text-secondary mb-1">Email:</label>
                        <input type="email" name="payload[email]" id="payload_email"
                            value="{{ old('payload.email', $payload['email'] ?? '') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                </div>

                <div class="pt-6 border-t border-dashed border-border-color">
                    <h3 class="text-md font-bold text-yellow-400">> ACADEMIC INFORMATION SYSTEM (SIA)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="payload_npm" class="block text-sm text-secondary mb-1">NPM / Student ID:</label>
                            <input type="text" name="payload[npm]" id="payload_npm"
                                value="{{ old('payload.npm', $payload['npm'] ?? '') }}"
                                class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                        </div>
                        <div>
                            <label for="payload_sia_password" class="block text-sm text-secondary mb-1">SIA Password
                                (leave blank to keep unchanged):</label>
                            <input type="password" name="payload[sia_password]" id="payload_sia_password"
                                class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                        </div>
                    </div>
                </div>

                {{-- ================================================================ --}}
                {{-- == FITUR BARU: BIDANG DATA DINAMIS == --}}
                {{-- ================================================================ --}}
                <div x-data="{ customFields: {{ json_encode($customFields) }} }"
                    class="pt-6 border-t-2 border-dashed border-border-color space-y-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-bold text-primary">> ADDITIONAL DATA FIELDS</h2>
                        <button type="button" @click="customFields.push({ type: 'text', label: '', value: '' })"
                            class="bg-surface-light border border-border-color text-secondary px-3 py-1 text-xs hover:border-primary hover:text-primary">
                            + ADD FIELD
                        </button>
                    </div>

                    <template x-for="(field, index) in customFields" :key="index">
                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 p-3 border border-border-color bg-base items-end">
                            <!-- Tipe Bidang -->
                            <div>
                                <label :for="'field_type_' + index" class="block text-sm text-secondary mb-1">Field
                                    Type</label>
                                <select :name="'payload[custom_fields][' + index + '][type]'" x-model="field.type"
                                    class="w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                                    <option class="text-black" value="text">Text</option>
                                    <option class="text-black" value="password">Password</option>
                                    <option class="text-black" value="url">URL</option>
                                    <option class="text-black" value="date">Date</option>
                                </select>
                            </div>
                            <!-- Label Bidang -->
                            <div>
                                <label :for="'field_label_' + index" class="block text-sm text-secondary mb-1">Field
                                    Label</label>
                                <input type="text" :name="'payload[custom_fields][' + index + '][label]'"
                                    x-model="field.label" placeholder="e.g., PIN Koper"
                                    class="w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                            </div>
                            <!-- Nilai Bidang -->
                            <div>
                                <label :for="'field_value_' + index" class="block text-sm text-secondary mb-1">Field
                                    Value</label>
                                <div class="flex items-center gap-2">
                                    <input
                                        :type="field.type === 'password' ? 'password' : (field.type === 'date' ? 'date' : 'text')"
                                        :name="'payload[custom_fields][' + index + '][value]'" x-model="field.value"
                                        class="w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                                    <button type="button" @click="customFields.splice(index, 1)"
                                        class="text-red-500 hover:text-red-400 p-2 bg-surface border border-border-color">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center pt-4">
                    {{-- Tombol Simpan --}}
                    <button type="submit"
                        class="bg-primary text-primary font-bold py-3 px-8 hover:bg-primary-hover transition-colors tracking-widest cursor-pointer">
                        SAVE & RE-ENCRYPT
                    </button>
                </div>
        </form>
    </div>
    @else
    {{-- TAMPILAN FORM MASTER PASSWORD --}}
    <div class="max-w-xl mx-auto bg-surface border-2 border-yellow-500/50 p-6 font-mono">
        <h2 class="text-lg font-bold text-yellow-400 border-b border-yellow-500/30 pb-2 mb-4">> AUTHORIZATION REQUIRED
        </h2>
        <p class="text-sm text-secondary mb-4">Enter your Master Password to decrypt and edit the data for this contact.
        </p>
        <form action="{{ route('encrypted-contacts.unlock', ['encryptedContact' => $contact, 'redirect' => 'edit']) }}"
            method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="master_password" class="block text-sm text-secondary mb-2">Master Password:</label>
                <input type="password" name="master_password" id="master_password" required autofocus
                    class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="bg-primary text-primary font-bold py-2 px-6 hover:bg-primary-hover transition-colors cursor-pointer">
                    AUTHORIZE & EDIT
                </button>
            </div>
        </form>
    </div>
    @endif
</x-app-layout>