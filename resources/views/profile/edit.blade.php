<x-app-layout theme="terminal">
    <x-slot:title>
        Edit Profile
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface/50 border border-border-color rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-primary text-glow"> > [ UPDATE PERSONAL DOSSIER ] </h2>
            <a href="{{ route('profile.show') }}" class="text-secondary hover:text-primary transition-colors text-sm text-glow">&lt; Cancel Update</a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-primary/10 border-l-4 border-primary text-primary-hover p-4 rounded-r-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
                <p class="font-bold">> Data Input Anomaly Detected:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH') {{-- Menggunakan method PATCH untuk update --}}

            <div class="space-y-6 text-glow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-primary text-sm">> REAL NAME</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                    <div>
                        <label for="codename" class="block text-primary text-sm">> CODENAME</label>
                        <input type="text" id="codename" name="codename" value="{{ old('codename', $user->codename) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                    <div>
                        <label for="username" class="block text-primary text-sm">> USERNAME (Login ID)</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                    <div>
                        <label for="email" class="block text-primary text-sm">> EMAIL (Recovery)</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                </div>

                <div class="border-t border-border-color/50 pt-6">
                    <p class="text-secondary mb-4">// Update Passcode (leave blank if not changing)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-primary text-sm">> NEW PASSCODE</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-primary text-sm">> CONFIRM NEW PASSCODE</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                        </div>
                    </div>
                </div>

                <div class="border-t border-border-color pt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary text-base hover:bg-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
                        [ SAVE CHANGES ]
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>