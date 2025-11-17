@props([
    'contact' => null, // Null jika Create, Model jika Edit
    'payload' => [],   // Data terdekripsi (hanya ada saat Edit & Unlock sukses)
])

@php
    $isEdit = !is_null($contact);
    $action = $isEdit ? route('encrypted-contacts.update', $contact) : route('encrypted-contacts.store');
    
    // Persiapan Data Custom Fields (Priority: Old Input > Decrypted Payload > Empty Array)
    $customFields = old('payload.custom_fields', $payload['custom_fields'] ?? []);
    if (!is_array($customFields)) {
        $customFields = [];
    }

    // Opsi Gender
    $genderOptions = [
        'Male'   => 'Male',
        'Female' => 'Female',
        'Other'  => 'Other',
    ];
@endphp

<form action="{{ $action }}" enctype="multipart/form-data" method="POST" class="space-y-8">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- 
        BAGIAN 1: DATA TIDAK TERENKRIPSI 
    --}}
    <div class="pt-6 border-t border-dashed border-border-color">
        <h2 class="text-lg font-bold text-primary mb-4">> PROFILE PHOTO {{ $isEdit ? '' : '(OPTIONAL)' }}</h2>
        
        @if($isEdit && $contact->profile_photo_path)
            <div class="mb-4 flex items-center gap-4">
                <img src="{{ asset($contact->profile_photo_path) }}" alt="{{ $contact->codename }}" 
                     class="w-24 h-24 object-cover border-2 border-border-color rounded-md">
                <p class="text-sm text-secondary">// Current active profile image</p>
            </div>
        @endif

        <div class="p-3 border border-border-color bg-base rounded-md">
             <input type="file" name="profile_photo" id="profile_photo"
                class="block w-full text-sm text-secondary 
                file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded-sm
                file:text-sm file:font-semibold file:bg-primary file:text-black 
                hover:file:bg-primary-hover cursor-pointer">
        </div>
        <p class="text-xs text-secondary mt-2">Main visual identifier for this contact.</p>
    </div>

    <div>
        <label for="codename" class="block text-primary text-lg mb-1">> CODENAME:</label>
        <p class="text-xs text-secondary mb-2">This is the only unencrypted field, used for identification in lists.</p>
        <x-forms.input 
            type="text" 
            name="codename" 
            id="codename" 
            required 
            :value="old('codename', $contact->codename ?? '')"
            placeholder="e.g., Viper, Echo-7"
        />
    </div>

    {{-- 
        BAGIAN 2: DATA TERENKRIPSI 
    --}}
    <div class="pt-6 border-t-2 border-dashed border-border-color space-y-6">
        <h2 class="text-lg font-bold text-primary">> ENCRYPTED DATA PAYLOAD</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Real Name --}}
            <div>
                <label class="block text-sm text-secondary mb-1">Real Name:</label>
                <x-forms.input 
                    type="text" 
                    name="payload[real_name]" 
                    id="payload_real_name"
                    :value="old('payload.real_name', $payload['real_name'] ?? '')"
                />
            </div>
            {{-- Jenis Kelamin --}}
            <div>
                <label class="block text-sm text-secondary mb-1">Gender:</label>
                <x-forms.select 
                    name="payload[gender]" 
                    id="payload_gender"
                    :options="$genderOptions"
                    :selected="old('payload.gender', $payload['gender'] ?? '')"
                    placeholder="-- Select --"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tempat Lahir --}}
            <div>
                <label class="block text-sm text-secondary mb-1">Place of Birth:</label>
                <x-forms.input 
                    type="text" 
                    name="payload[pob]" 
                    id="payload_pob" 
                    :value="old('payload.pob', $payload['pob'] ?? '')"
                />
            </div>
            {{-- Tanggal Lahir --}}
            <div>
                <label class="block text-sm text-secondary mb-1">Date of Birth:</label>
                <x-forms.input 
                    type="date" 
                    name="payload[dob]" 
                    id="payload_dob" 
                    :value="old('payload.dob', $payload['dob'] ?? '')"
                />
            </div>
        </div>

        {{-- Alamat --}}
        <div>
            <label class="block text-sm text-secondary mb-1">Address:</label>
            <x-forms.textarea 
                name="payload[address]" 
                id="payload_address" 
                rows="3" 
                :value="old('payload.address', $payload['address'] ?? '')"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nomor HP/WA --}}
            <div>
                <label class="block text-sm text-secondary mb-1">Phone / WhatsApp:</label>
                <x-forms.input 
                    type="text" 
                    name="payload[phone]" 
                    id="payload_phone" 
                    :value="old('payload.phone', $payload['phone'] ?? '')"
                />
            </div>
            {{-- Email --}}
            <div>
                <label class="block text-sm text-secondary mb-1">Email:</label>
                <x-forms.input 
                    type="email" 
                    name="payload[email]" 
                    id="payload_email" 
                    :value="old('payload.email', $payload['email'] ?? '')"
                />
            </div>
        </div>

        {{-- Informasi Akademik --}}
        <div class="pt-6 border-t border-dashed border-border-color">
            <h3 class="text-md font-bold text-yellow-400 mb-4">> ACADEMIC INFORMATION SYSTEM (SIA)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-secondary mb-1">NPM / Student ID:</label>
                    <x-forms.input 
                        type="text" 
                        name="payload[npm]" 
                        id="payload_npm" 
                        :value="old('payload.npm', $payload['npm'] ?? '')"
                    />
                </div>
                <div>
                    <label class="block text-sm text-secondary mb-1">SIA Password:</label>
                    <x-forms.input 
                        type="password" 
                        name="payload[sia_password]" 
                        id="payload_sia_password" 
                        :value="old('payload.sia_password', $payload['sia_password'] ?? '')"
                        :placeholder="$isEdit ? '(Leave blank to keep unchanged)' : ''"
                    />
                </div>
            </div>
        </div>
    </div>

    {{-- 
        BAGIAN 3: BIDANG DATA DINAMIS (AlpineJS) 
    --}}
    <div x-data="{ customFields: {{ json_encode($customFields) }} }" class="pt-6 border-t-2 border-dashed border-border-color space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary">> ADDITIONAL DATA FIELDS</h2>
            <button type="button" @click="customFields.push({ type: 'text', label: '', value: '' })" 
                class="btn btn-sm btn-outline">
                + ADD FIELD
            </button>
        </div>

        <template x-for="(field, index) in customFields" :key="index">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-3 border border-border-color bg-base items-end mb-4">
                
                <div>
                    <label :for="'field_type_' + index" class="block text-sm text-secondary mb-1">Field Type</label>
                    <select 
                        :name="'payload[custom_fields][' + index + '][type]'" 
                        x-model="field.type"
                        class="form-control cursor-pointer">
                        <option value="text">Text</option>
                        <option value="password">Password</option>
                        <option value="url">URL</option>
                        <option value="date">Date</option>
                    </select>
                </div>
                
                <div>
                    <label :for="'field_label_' + index" class="block text-sm text-secondary mb-1">Field Label</label>
                    <input type="text" 
                        :name="'payload[custom_fields][' + index + '][label]'" 
                        x-model="field.label" 
                        placeholder="e.g., Secret Code" 
                        class="form-control">
                </div>
                
                <div>
                    <label :for="'field_value_' + index" class="block text-sm text-secondary mb-1">Field Value</label>
                    <div class="flex items-center gap-2">
                        <input 
                            :type="field.type === 'password' ? 'password' : (field.type === 'date' ? 'date' : 'text')" 
                            :name="'payload[custom_fields][' + index + '][value]'" 
                            x-model="field.value" 
                            class="form-control">
                            
                        <button type="button" @click="customFields.splice(index, 1)" 
                            class="p-2 border border-border-color bg-surface hover:bg-red-900/20 text-red-500 hover:text-red-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
        
        <div x-show="customFields.length === 0" class="text-center text-sm text-secondary italic py-4">
            No additional fields added.
        </div>
    </div>

    <div class="flex justify-end pt-4 border-t border-border-color">
        <x-button type="submit">
            {{ $isEdit ? 'SAVE & RE-ENCRYPT' : 'SAVE & ENCRYPT' }}
        </x-button>
    </div>
</form>