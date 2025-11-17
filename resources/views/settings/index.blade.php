<x-app-layout title=" {{ __('System Settings') }}">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <h1 class="text-4xl font-bold text-primary tracking-widest font-mono text-glow">
            > {{ __('System Settings') }}
        </h1>
        <p class="text-sm text-secondary font-mono mt-1"> {{ __('General application configuration.') }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Semua panel pengaturan sekarang berada di dalam satu form dan satu grid --}}
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Panel Pengaturan Bahasa --}}
            <div class="bg-surface border border-border-color p-6 font-mono">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> {{ __('Language Setting') }}</h2>
                <label for="locale" class="block text-sm text-secondary mb-2">{{ __('Select Terminal Language:') }}</label>
                <select name="locale" id="locale" class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    {{-- Logika @selected diperbaiki --}}
                    <option class="text-black" value="en" @selected((Auth::user()->settings['locale'] ?? 'en') == 'en')>English</option>
                    <option class="text-black" value="id" @selected((Auth::user()->settings['locale'] ?? 'en') == 'id')>Bahasa Indonesia</option>
                </select>
            </div>

            {{-- Panel Pengaturan Tampilan Data --}}
            <div class="bg-surface border border-border-color p-6 font-mono">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> {{ __('Data Display Setting') }}</h2>
                <label for="per_page" class="block text-sm text-secondary mb-2">{{ __('Entries Per Page:') }}</label>
                <select name="per_page" id="per_page" class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    @php $currentPerPage = Auth::user()->settings['per_page'] ?? 9; @endphp
                    <option class="text-black" value="6" @selected($currentPerPage == 6)>{{ __('6 Entries') }}</option>
                    <option class="text-black" value="9" @selected($currentPerPage == 9)>{{ __('9 Entries') }}</option>
                    <option class="text-black" value="12" @selected($currentPerPage == 12)>{{ __('12 Entries') }}</option>
                    <option class="text-black" value="15" @selected($currentPerPage == 15)>{{ __('15 Entries') }}</option>  
                    <option class="text-black" value="18" @selected($currentPerPage == 18)>{{ __('18 Entries') }}</option>
                    <option class="text-black" value="27" @selected($currentPerPage == 27)>{{ __('27 Entries') }}</option>
                    <option class="text-black" value="54" @selected($currentPerPage == 54)>{{ __('54 Entries') }}</option>
                </select>
            </div>

            {{-- Panel Kustomisasi Tema --}}
            <div class="bg-surface border border-border-color p-6 font-mono">
                <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> {{ __('UI Theme') }}</h2>
                <label for="theme" class="block text-sm text-secondary mb-2">{{ __('Select Terminal Theme:') }}</label>
                <select name="theme" id="theme" class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                    @php $currentTheme = Auth::user()->settings['theme'] ?? 'default'; @endphp
                    <option class="text-green-500" value="default" @selected($currentTheme == 'default')>Green Phosphorus (Default)</option>
                    <option class="text-orange-500" value="amber" @selected($currentTheme == 'amber')>Amber Fallout</option>
                    <option class="text-blue-500" value="arctic" @selected($currentTheme == 'arctic')>Arctic Blue</option>
                    <option class="text-red-500" value="red" @selected($currentTheme == 'red')>Code Red</option>
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-button type="submit">
                {{ __('SAVE ALL CHANGES') }}
            </x-button>
        </div>
    </form>
</x-app-layout>
