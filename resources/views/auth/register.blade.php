<x-app-layout>
    <x-slot:title>
        Register New Agent
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-primary">
                > [ NEW AGENT FORM ]
            </h2>
            <div class="mt-3 sm:mt-2 flex sm:justify-end">
                <a href="{{ url()->previous() }}" class="text-secondary hover:text-primary transition-colors text-sm">
                    &lt; Back
                </a>
            </div>
        </div>

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
        
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            
            {{-- Menggunakan Grid untuk layout responsif --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-primary text-sm">> REAL NAME</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div>
                    <label for="codename" class="block text-primary text-sm">> CODENAME</label>
                    <input type="text" id="codename" name="codename" value="{{ old('codename') }}" required
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div>
                    <label for="username" class="block text-primary text-sm">> USERNAME (Login ID)</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div>
                    <label for="email" class="block text-primary text-sm">> EMAIL (Recovery)</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div>
                    <label for="password" class="block text-primary text-sm">> PASSCODE</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-primary text-sm">> CONFIRM PASSCODE</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                </div>
            </div>

            <div>
                <label for="role_id" class="block text-primary text-sm">> ASSIGN ROLE</label>
                <select id="role_id" name="role_id" required
                        class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                    <option value="" disabled selected>-- Select Designation --</option>
                    {{-- Loop dari variabel $roles yang dikirim Controller --}}
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->alias }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="border-t border-border-color pt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-base text-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
                    [ CREATE DOSSIER ]
                </button>
            </div>
        </form>
    </div>
</x-app-layout>