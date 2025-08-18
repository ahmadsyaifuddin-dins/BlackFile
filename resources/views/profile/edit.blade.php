<x-app-layout theme="terminal">
    <x-slot:title>
        Edit Profile
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface/50 border border-border-color rounded-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-primary text-glow"> > [ UPDATE PERSONAL ] </h2>
            <div class="mt-3 sm:mt-2 flex sm:justify-end">
                <a href="{{ route('profile.show') }}"
                    class="text-secondary hover:text-primary transition-colors text-sm text-glow">&lt; Cancel Update</a>
            </div>
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

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-6 text-glow">
                <!-- [DIUBAH] Bagian Upload Avatar Responsif -->
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->codename) }}" 
                         alt="Avatar" class="w-20 h-20 rounded-full border-2 border-border-color flex-shrink-0">
                    <div class="w-full sm:w-auto">
                        <label for="avatar" class="block text-primary/25 text-sm">> UPLOAD NEW AVATAR</label>
                        <input type="file" id="avatar" name="avatar" class="mt-1 text-sm text-secondary
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary/20 file:text-primary-hover
                            hover:file:bg-primary/40">
                    </div>
                </div>

                <!-- Bagian Info Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-border-color/50 pt-6">
                    <div>
                        <label for="name" class="block text-primary/25 text-sm">> REAL NAME</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                    <div>
                        <label for="codename" class="block text-primary/25 text-sm">> CODENAME</label>
                        <input type="text" id="codename" name="codename" value="{{ old('codename', $user->codename) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                    <div>
                        <label for="username" class="block text-primary/25 text-sm">> USERNAME (Login ID)</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>
                    <div>
                        <label for="email" class="block text-primary/25 text-sm">> EMAIL (Recovery)</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                    </div>

                    <div>
                        <label for="specialization" class="block text-primary/25 text-sm">> SPECIALIZATION</label>
                        <input type="text" id="specialization" name="specialization" value="{{ old('specialization', $user->specialization) }}"
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded"
                               placeholder="e.g., Digital Research, Data Analysis">
                    </div>
                    <div>
                        <label for="quotes" class="block text-primary/25 text-sm">> PERSONAL QUOTES</label>
                        <textarea id="quotes" name="quotes"
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded"
                               placeholder="e.g., Fortune favors the bold.">{{ old('quotes', $user->quotes) }}</textarea>
                    </div>
                </div>

                <!-- Bagian Password -->
                <div class="border-t border-border-color/50 pt-6">
                    <p class="text-secondary mb-4 text-sm">// Update Passcode (leave blank if not changing)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-primary/25 text-sm">> NEW PASSCODE</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-primary/25 text-sm">> CONFIRM NEW PASSCODE</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded">
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="border-t border-border-color pt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary text-base text-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
                        [ SAVE CHANGES ]
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>