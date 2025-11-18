 {{-- 
        [DATA PREPARATION - LOCALIZATION FIX]
        Kita bungkus value dengan fungsi helper __() agar teks diterjemahkan 
        sesuai dengan file lang/id.json.
    --}}
    @php
        // 1. Opsi Bahasa (Label manual karena nama bahasa biasanya tidak diterjemahkan)
        $localeOptions = [
            'en' => 'English (International)',
            'id' => 'Bahasa Indonesia',
        ];

        // 2. Opsi Paginasi (Dibungkus __() agar mengambil dari id.json)
        // Pastikan string di dalam __('') sama persis dengan key di id.json
        $perPageOptions = [
            6  => __('6 Entries'),
            9  => __('9 Entries'),
            12 => __('12 Entries'),
            15 => __('15 Entries'),
            18 => __('18 Entries'),
            27 => __('27 Entries'),
            54 => __('54 Entries'),
        ];

        // 3. Opsi Tema
        // Nama tema biasanya proper noun, tapi jika ingin diterjemahkan bisa dimasukkan ke json juga.
        // Untuk saat ini kita biarkan English, atau bungkus __() jika mau ditambahkan ke json nanti.
        $themeOptions = [
            'default' => 'Green Phosphorus (Default)',
            'amber'   => 'Amber Fallout',
            'arctic'  => 'Arctic Blue',
            'red'     => 'Code Red',
        ];
    @endphp
<x-app-layout title="{{ __('System Settings') }}">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <h1 class="text-xl md:text-2xl font-bold text-primary tracking-widest font-mono text-glow">
            > {{ __('System Settings') }}
        </h1>
        <p class="text-sm text-secondary font-mono mt-1"> {{ __('General application configuration.') }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative mb-6 font-mono text-sm" role="alert">
        <span class="font-bold">> STATUS:</span> {{ session('success') }}
    </div>
    @endif

    {{-- Form Utama --}}
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Panel 1: Pengaturan Bahasa --}}
            <div class="bg-surface border border-border-color p-6 font-mono h-full">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> {{ __('Language Setting') }}</h2>
                
                <x-forms.select 
                    label="{{ __('Select Terminal Language:') }}"
                    name="locale" 
                    :options="$localeOptions" 
                    :selected="Auth::user()->settings['locale'] ?? 'en'" 
                />
                
                <p class="text-xs text-secondary mt-2">
                    {{ __('// Affects system labels and messages.') }}
                </p>
            </div>

            {{-- Panel 2: Pengaturan Tampilan Data --}}
            <div class="bg-surface border border-border-color p-6 font-mono h-full">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> {{ __('Data Display Setting') }}</h2>
                
                <x-forms.select 
                    label="{{ __('Entries Per Page:') }}"
                    name="per_page" 
                    :options="$perPageOptions" 
                    :selected="Auth::user()->settings['per_page'] ?? 9" 
                />
                
                <p class="text-xs text-secondary mt-2">
                    {{ __('// Controls grid density in Archives & Entities.') }}
                </p>
            </div>

            {{-- Panel 3: Kustomisasi Tema --}}
            <div class="bg-surface border border-border-color p-6 font-mono h-full">
                {{-- Menggunakan key 'UI Theme' agar sesuai id.json --}}
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> {{ __('UI Theme') }}</h2>
                
                {{-- Menggunakan key 'Select Terminal Theme:' agar sesuai id.json --}}
                <x-forms.select 
                    label="{{ __('Select Terminal Theme:') }}"
                    name="theme" 
                    :options="$themeOptions" 
                    :selected="Auth::user()->settings['theme'] ?? 'default'" 
                />

                <p class="text-xs text-secondary mt-2">
                    {{ __('// Requires page refresh to fully apply effects.') }}
                </p>
            </div>

        </div>

        {{-- Tombol Simpan --}}
        <div class="mt-6 flex justify-end pt-4 border-t border-border-color">
            <x-button type="submit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                {{-- Menggunakan key 'SAVE ALL CHANGES' agar sesuai id.json --}}
                <span>{{ __('SAVE ALL CHANGES') }}</span>
            </x-button>
        </div>
    </form>
</x-app-layout>