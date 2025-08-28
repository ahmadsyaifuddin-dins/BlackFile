<x-app-layout title="Add New Encrypted Contact">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold text-primary tracking-widest font-mono">
                    > ADD NEW ENCRYPTED CONTACT
                </h1>
            </div>
            <a href="{{ route('encrypted-contacts.index') }}"
                class="w-full sm:w-auto text-center bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 hover:text-primary hover:border-primary transition-colors">
                &lt; CANCEL & RETURN TO VAULT
            </a>
        </div>
    </div>

    <div class="bg-surface border border-border-color p-6 font-mono">
        <form action="{{ route('encrypted-contacts.store') }}" enctype="multipart/form-data" method="POST"
            class="space-y-8">
            @csrf

            {{-- Variabel didefinisikan di sini --}}
            @php
            $customFields = old('payload.custom_fields', []);
            if (!is_array($customFields)) {
            $customFields = [];
            }
            @endphp

            {{-- Data Tidak Terenkripsi --}}

            <div class="pt-6 border-t border-dashed border-border-color">
                <label for="profile_photo" class="text-primary text-lg">> PROFILE PHOTO (OPTIONAL)</label>
                <p class="text-xs text-secondary mb-2">Main visual identifier for this contact.</p>
                <input type="file" name="profile_photo" id="profile_photo"
                    class="block w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-primary hover:file:bg-primary-hover cursor-pointer">
            </div>

            <div>
                <label for="codename" class="flex-shrink-0 text-primary text-lg">> CODENAME:</label>
                <p class="text-xs text-secondary mb-2">This is the only unencrypted field, used for identification in
                    lists.</p>
                <input type="text" name="codename" id="codename" required value="{{ old('codename') }}"
                    placeholder="e.g., Viper, Echo-7"
                    class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
            </div>

            {{-- Data Terenkripsi --}}
            <div class="pt-6 border-t-2 border-dashed border-border-color space-y-6">
                <h2 class="text-lg font-bold text-primary">> ENCRYPTED DATA PAYLOAD</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Asli --}}
                    <div>
                        <label for="payload_real_name" class="block text-sm text-secondary mb-1">Real Name:</label>
                        <input type="text" name="payload[real_name]" id="payload_real_name"
                            value="{{ old('payload.real_name') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="payload_gender" class="block text-sm text-secondary mb-1">Gender:</label>
                        <select name="payload[gender]" id="payload_gender"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                            <option value="">-- Select --</option>
                            <option class="text-black" value="Male">Male</option>
                            <option class="text-black" value="Female">Female</option>
                            <option class="text-black" value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tempat Lahir --}}
                    <div>
                        <label for="payload_pob" class="block text-sm text-secondary mb-1">Place of Birth:</label>
                        <input type="text" name="payload[pob]" id="payload_pob" value="{{ old('payload.pob') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="payload_dob" class="block text-sm text-secondary mb-1">Date of Birth:</label>
                        <input type="date" name="payload[dob]" id="payload_dob" value="{{ old('payload.dob') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="payload_address" class="block text-sm text-secondary mb-1">Address:</label>
                    <textarea name="payload[address]" id="payload_address" rows="3"
                        class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">{{ old('payload.address') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nomor HP/WA --}}
                    <div>
                        <label for="payload_phone" class="block text-sm text-secondary mb-1">Phone / WhatsApp:</label>
                        <input type="text" name="payload[phone]" id="payload_phone" value="{{ old('payload.phone') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                    {{-- Email --}}
                    <div>
                        <label for="payload_email" class="block text-sm text-secondary mb-1">Email:</label>
                        <input type="email" name="payload[email]" id="payload_email" value="{{ old('payload.email') }}"
                            class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    </div>
                </div>

                <div class="pt-6 border-t border-dashed border-border-color">
                    <h3 class="text-md font-bold text-yellow-400">> ACADEMIC INFORMATION SYSTEM (SIA)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label for="payload_npm" class="block text-sm text-secondary mb-1">NPM / Student ID:</label>
                            <input type="text" name="payload[npm]" id="payload_npm" value="{{ old('payload.npm') }}"
                                class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                        </div>
                        <div>
                            <label for="payload_sia_password" class="block text-sm text-secondary mb-1">SIA
                                Password:</label>
                            <input type="password" name="payload[sia_password]" id="payload_sia_password"
                                class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bidang Data Dinamis --}}
            <div x-data="{ customFields: {{ json_encode($customFields) }} }" class="pt-6 border-t-2 border-dashed border-border-color space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-primary">> ADDITIONAL DATA FIELDS</h2>
                    <button type="button" @click="customFields.push({ type: 'text', label: '', value: '' })" class="bg-surface-light border border-border-color text-secondary px-3 py-1 text-xs hover:border-primary hover:text-primary">
                        + ADD FIELD
                    </button>
                </div>

                <template x-for="(field, index) in customFields" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-3 border border-border-color bg-base items-end">
                        <!-- Tipe Bidang -->
                        <div>
                            <label :for="'field_type_' + index" class="block text-sm text-secondary mb-1">Field Type</label>
                            <select :name="'payload[custom_fields][' + index + '][type]'" x-model="field.type" class="w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                                <option class="text-black" value="text">Text</option>
                                <option class="text-black" value="password">Password</option>
                                <option class="text-black" value="url">URL</option>
                                <option class="text-black" value="date">Date</option>
                            </select>
                        </div>
                        <!-- Label Bidang -->
                        <div>
                            <label :for="'field_label_' + index" class="block text-sm text-secondary mb-1">Field Label</label>
                            <input type="text" :name="'payload[custom_fields][' + index + '][label]'" x-model="field.label" placeholder="e.g., PIN Koper" class="w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                        </div>
                        <!-- Nilai Bidang -->
                        <div>
                            <label :for="'field_value_' + index" class="block text-sm text-secondary mb-1">Field Value</label>
                            <div class="flex items-center gap-2">
                                <input :type="field.type === 'password' ? 'password' : (field.type === 'date' ? 'date' : 'text')" :name="'payload[custom_fields][' + index + '][value]'" x-model="field.value" class="w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                                <button type="button" @click="customFields.splice(index, 1)" class="text-red-500 hover:text-red-400 p-2 bg-surface border border-border-color">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-primary text-black font-bold py-3 px-8 hover:bg-primary-hover transition-colors tracking-widest cursor-pointer">
                    SAVE & ENCRYPT
                </button>
            </div>
        </form>
    </div>
</x-app-layout>