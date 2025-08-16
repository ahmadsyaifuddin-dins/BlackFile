<x-app-layout>
    <x-slot:title>
        Establish New Connection
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-primary">
                > [ ESTABLISH NEW CONNECTION ]
            </h2>
            <a href="{{ route('friends.index') }}" class="text-secondary hover:text-primary transition-colors text-sm">
                &lt; Back to Network
            </a>
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
        
        <form method="POST" action="{{ route('friends.store') }}">
            @csrf
            
            {{-- ====================================================== --}}
            {{-- BAGIAN INI MENGGANTIKAN FORM LAMA ANDA --}}
            {{-- ====================================================== --}}

            <!-- Mode 1: Daftarkan Aset Baru -->
            <div class="border-b border-border-color pb-6">
                <h3 class="text-lg text-primary font-bold mb-4">> OPTION A: REGISTER NEW ASSET</h3>
                <p class="text-sm text-secondary mb-4">// Use this to add a new informant or asset that is not a system user.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-primary text-sm">> REAL NAME</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                    </div>
                    <div>
                        <label for="codename" class="block text-primary text-sm">> CODENAME</label>
                        <input type="text" id="codename" name="codename" value="{{ old('codename') }}"
                               class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary placeholder-secondary/50 p-2 rounded">
                    </div>
                </div>
            </div>

            <!-- Pembatas -->
            <div class="text-center text-secondary my-4 font-bold tracking-widest">OR</div>

            <!-- Mode 2: Hubungkan ke yang Sudah Ada -->
            <div>
                <h3 class="text-lg text-primary font-bold mb-4">> OPTION B: CONNECT TO EXISTING ENTITY</h3>
                <p class="text-sm text-secondary mb-4">// Use this to link to another registered operative or an existing asset.</p>
                <div>
                    <label for="target_entity" class="block text-primary text-sm">> SELECT TARGET</label>
                    <select id="target_entity" name="target_entity"
                            class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        <option value="">-- Select Entity --</option>
                        <optgroup label="OPERATIVES (USERS)">
                            @foreach($connectableUsers as $user)
                                <option value="user-{{ $user->id }}">{{ $user->codename }} ({{ $user->role->alias }})</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="EXISTING ASSETS (FRIENDS)">
                            @foreach($connectableFriends as $friend)
                                <option value="friend-{{ $friend->id }}">{{ $friend->codename }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
            
            {{-- ====================================================== --}}
            {{-- AKHIR DARI BAGIAN YANG BARU --}}
            {{-- ====================================================== --}}

            <div class="border-t border-border-color pt-6 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-base hover:text-green-700 transition-colors font-bold tracking-widest rounded-md text-sm cursor-pointer">
                    [ ESTABLISH CONNECTION ]
                </button>
            </div>
        </form>
    </div>
</x-app-layout>