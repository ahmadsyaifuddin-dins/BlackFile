<x-app-layout>
    <x-slot:title>
        {{ __('Establish New Connection') }}
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h2 class="text-base sm:text-2xl font-bold text-primary">
                    > [ {{ __('ESTABLISH NEW CONNECTION') }} ]
                </h2>
            </div>
            <x-button variant="outline" href="{{ url()->previous() }}">
                &lt; {{ __('Back to Network') }}
            </x-button>
        </div>

        @if($errors->any())
        <div class="mb-6 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg font-mono text-sm" role="alert">
            <p class="font-bold mb-2">> {{ __('Data Input Anomaly Detected') }}:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('friends.store') }}">
            @csrf
            
            {{-- 
                [MODE 1] Daftarkan Aset Baru 
            --}}
            <div class="pb-6">
                <h3 class="text-base text-primary font-bold mb-2">> [ {{ __('OPTION A: REGISTER NEW ASSET') }} ]</h3>
                <p class="text-sm text-secondary mb-4 font-mono">// {{ __('Use this to add a new informant or asset that is not a system user.') }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Real Name --}}
                    <div>
                        <label for="name" class="block text-primary text-sm mb-1">> REAL NAME</label>
                        <x-forms.input 
                            type="text" 
                            id="name" 
                            name="name" 
                            :value="old('name')" 
                        />
                    </div>
                    
                    {{-- Codename --}}
                    <div>
                        <label for="codename" class="block text-primary text-sm mb-1">> CODENAME</label>
                        <x-forms.input 
                            type="text" 
                            id="codename" 
                            name="codename" 
                            :value="old('codename')" 
                        />
                    </div>
                    
                    {{-- Category Dropdown (Pakai Component Kita) --}}
                    <div>
                        <x-forms.select 
                            label="> {{ __('ASSET CATEGORY') }}"
                            name="category" 
                            id="category"
                            :options="$categories" 
                            :selected="old('category')"
                            placeholder="{{ __('-- Select Category --') }}"
                            :searchable="true"
                        />
                    </div>
                </div>
            </div>

            {{-- Pembatas Visual --}}
            <div class="relative flex py-2 items-center">
                <div class="flex-grow border-t border-border-color"></div>
                <span class="flex-shrink-0 mx-4 text-secondary font-bold tracking-widest bg-surface px-2">{{ __('OR') }}</span>
                <div class="flex-grow border-t border-border-color"></div>
            </div>

            {{-- 
                [MODE 2] Hubungkan ke yang Sudah Ada 
            --}}
            <div class="mt-6">
                <h3 class="text-base text-primary font-bold mb-2">> [ {{ __('OPTION B: CONNECT TO EXISTING ENTITY') }} ]</h3>
                <p class="text-sm text-secondary mb-4 font-mono">// {{ __('Use this to link to another registered operative or an existing asset.') }}</p>
                
                <div>
                    <label for="target_entity" class="block text-primary text-sm mb-1">> {{ __('SELECT TARGET') }}</label>
                    
                    {{-- 
                        [CATATAN PENTING]
                        Karena kita butuh <optgroup>, kita gunakan Native Select tapi dengan class .form-control
                        agar tampilannya 100% konsisten dengan x-forms.select component.
                    --}}
                    <div class="relative font-mono w-full">
                        <select id="target_entity" name="target_entity" 
                            class="form-control cursor-pointer appearance-none">
                            <option value="">{{ __('-- Select Entity --') }}</option>
                            
                            <optgroup class="bg-surface text-primary font-bold" label="{{ __('OPERATIVES (USERS)') }}">
                                @foreach($connectableUsers as $user)
                                <option class="text-secondary font-normal bg-black" value="user-{{ $user->id }}" 
                                    {{ old('target_entity') == "user-$user->id" ? 'selected' : '' }}>
                                    {{ $user->codename }} ({{ $user->role->alias }})
                                </option>
                                @endforeach
                            </optgroup>
                            
                            <optgroup class="bg-surface text-primary font-bold" label="{{ __('EXISTING ASSETS (FRIENDS)') }}">
                                @foreach($connectableFriends as $friend)
                                <option class="text-secondary font-normal bg-black" value="friend-{{ $friend->id }}"
                                    {{ old('target_entity') == "friend-$friend->id" ? 'selected' : '' }}>
                                    {{ $friend->codename }}
                                </option>
                                @endforeach
                            </optgroup>
                        </select>
                        
                        {{-- Chevron Icon Manual agar tampilan konsisten --}}
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-secondary">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-border-color mt-8 pt-6 flex justify-end">
                <x-button type="submit">
                    [ {{ __('ESTABLISH CONNECTION') }} ]
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>