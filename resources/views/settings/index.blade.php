<x-app-layout title="System Settings">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <h1 class="text-4xl font-bold text-primary tracking-widest font-mono text-glow">
            > SYSTEM SETTINGS
        </h1>
        <p class="text-sm text-secondary font-mono mt-1">General application configuration.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-900/50 border border-primary text-primary px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Panel Pengaturan Bahasa --}}
    <div class="bg-surface border border-border-color p-6 font-mono max-w-lg">
        <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> LANGUAGE SETTING</h2>
        
        <form action="{{ route('settings.updateLanguage') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="locale" class="block text-sm text-secondary mb-2">Select Terminal Language:</label>
                    <select name="locale" id="locale" class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                        {{-- Ambil bahasa saat ini dari session, default ke 'en' --}}
                        <option value="en" @selected(session('locale', 'en') == 'en')>English</option>
                        <option value="id" @selected(session('locale', 'en') == 'id')>Bahasa Indonesia</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-primary font-bold py-2 px-6 hover:bg-primary-hover transition-colors">
                        SAVE CHANGES
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
