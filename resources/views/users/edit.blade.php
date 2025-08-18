<x-app-layout>
    <x-slot:title>
        Edit Agent: {{ $user->codename }}
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <h2 class="text-2xl font-bold text-primary mb-6"> > [ EDITING AGENT DOSSIER: {{ $user->codename }} ] </h2>
        
        <form method="POST" action="{{ route('agents.update', $user->id) }}">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                {{-- Bagian Info Utama --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-primary text-sm">> REAL NAME</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                    </div>
                    <div>
                        <label for="codename" class="block text-primary text-sm">> CODENAME</label>
                        <input type="text" id="codename" name="codename" value="{{ old('codename', $user->codename) }}" required
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                    </div>
                    <div>
                        <label for="username" class="block text-primary text-sm">> USERNAME (Login ID)</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                    </div>
                    <div>
                        <label for="email" class="block text-primary text-sm">> EMAIL (Recovery)</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                    </div>
                    <div>
                        <label for="specialization" class="block text-primary text-sm">> SPECIALIZATION</label>
                        <input type="text" id="specialization" name="specialization" value="{{ old('specialization', $user->specialization) }}"
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded"
                               placeholder="e.g., Digital Research, Data Analysis">
                    </div>
                    <div>
                        <label for="quotes" class="block text-primary text-sm">> PERSONAL QUOTES</label>
                        <textarea id="quotes" name="quotes"
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary p-2 rounded"
                               placeholder="e.g., Fortune favors the bold.">{{ old('quotes', $user->quotes) }}</textarea>
                    </div>
                </div>
                
                {{-- Bagian Role --}}
                <div>
                    <label for="role_id" class="block text-primary text-sm">> ASSIGN ROLE</label>
                    <select id="role_id" name="role_id" required
                            class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->alias }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- [DIUBAH] Bagian Password --}}
                <div class="border-t border-border-color/50 pt-6">
                    <p class="text-secondary mb-4 text-sm">// Update Passcode (leave blank if not changing)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-primary text-sm">> NEW PASSCODE</label>
                            {{-- value dan required dihapus --}}
                            <input type="password" id="password" name="password"
                                   class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-primary text-sm">> CONFIRM NEW PASSCODE</label>
                             {{-- value dan required dihapus --}}
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                        </div>
                    </div>
                </div>

                <div class="border-t border-border-color pt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary text-base text-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
                        [ UPDATE DOSSIER ]
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>