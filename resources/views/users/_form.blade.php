@props([
    'user' => null, // Jika ada user, berarti mode EDIT. Jika null, mode CREATE.
    'roles' => [],  // Data roles wajib dikirim
])

@php
    // Deteksi mode berdasarkan keberadaan $user
    $isEdit = !is_null($user);
    
    // Tentukan Route & Method
    $action = $isEdit ? route('agents.update', $user->id) : route('register.agent');
    $method = $isEdit ? 'PATCH' : 'POST';

    // Persiapkan opsi Role untuk x-forms.select
    $roleOptions = $roles->pluck('alias', 'id')->toArray();
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PATCH')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Real Name --}}
        <div>
            <label for="name" class="block text-primary text-sm mb-1">> REAL NAME</label>
            <x-forms.input 
                type="text" 
                id="name" 
                name="name" 
                :value="old('name', $user->name ?? '')" 
                required 
            />
        </div>

        {{-- Codename --}}
        <div>
            <label for="codename" class="block text-primary text-sm mb-1">> CODENAME</label>
            <x-forms.input 
                type="text" 
                id="codename" 
                name="codename" 
                :value="old('codename', $user->codename ?? '')" 
                required 
            />
        </div>

        {{-- Username --}}
        <div>
            <label for="username" class="block text-primary text-sm mb-1">> USERNAME (Login ID)</label>
            <x-forms.input 
                type="text" 
                id="username" 
                name="username" 
                :value="old('username', $user->username ?? '')" 
                required 
            />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-primary text-sm mb-1">> EMAIL (Recovery)</label>
            <x-forms.input 
                type="email" 
                id="email" 
                name="email" 
                :value="old('email', $user->email ?? '')" 
                required 
            />
        </div>

        {{-- Specialization (Hanya di Edit User biasanya, tapi kita masukkan saja opsional) --}}
        <div>
            <label for="specialization" class="block text-primary text-sm mb-1">> SPECIALIZATION</label>
            <x-forms.input 
                type="text" 
                id="specialization" 
                name="specialization" 
                :value="old('specialization', $user->specialization ?? '')" 
            />
        </div>

        {{-- Personal Quotes (Hanya di Edit User, opsional) --}}
        <div>
            <label for="quotes" class="block text-primary text-sm mb-1">> PERSONAL QUOTES</label>
            <x-forms.textarea 
                id="quotes" 
                name="quotes" 
                rows="1"
                :value="old('quotes', $user->quotes ?? '')" 
            />
        </div>

        {{-- Password Section --}}
        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-border-color">
            @if($isEdit)
            <div class="md:col-span-2">
                <p class="text-secondary text-xs mb-2">// Leave blank if you don't want to change the passcode.</p>
            </div>
            @endif

            <div>
                <label for="password" class="block text-primary text-sm mb-1">> {{ $isEdit ? 'NEW PASSCODE' : 'PASSCODE' }}</label>
                <x-forms.input 
                    type="password" 
                    id="password" 
                    name="password" 
                    :required="!$isEdit" 
                />
            </div>

            <div>
                <label for="password_confirmation" class="block text-primary text-sm mb-1">> CONFIRM PASSCODE</label>
                <x-forms.input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    :required="!$isEdit" 
                />
            </div>
        </div>
    </div>

    {{-- Role Selection --}}
    <div>
        <x-forms.select 
            label="> ASSIGN ROLE" 
            name="role_id" 
            id="role_id" 
            :options="$roleOptions"
            :selected="old('role_id', $user->role_id ?? '')" 
            placeholder="-- Select Designation --" 
            :searchable="true" 
        />
    </div>

    {{-- Submit Button --}}
    <div class="flex justify-end pt-4 border-t border-border-color">
        <x-button type="submit">
            [ {{ $isEdit ? 'UPDATE AGENT DATA' : 'CREATE AGENT' }} ]
        </x-button>
    </div>
</form>