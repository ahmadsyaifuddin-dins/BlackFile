<x-app-layout>
    <x-slot:title>
        {{ __('Establish New Connection') }}
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg">
        <div class="mb-6">
            <h2 class="text-base sm:text-2xl font-bold text-primary">
                > [ {{ __('ESTABLISH NEW CONNECTION') }} ]
            </h2>
            <a href="{{ url()->previous() }}" class="text-secondary hover:text-green-600 transition-colors text-sm">
                &lt; {{ __('Back to Network') }}
            </a>
        </div>

        @if($errors->any())
        <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
            <p class="font-bold">> {{ __('Data Input Anomaly Detected') }}:</p>
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
                <h3 class="text-base text-primary font-bold mb-4">> [ {{ __('OPTION A: REGISTER NEW ASSET') }} ]</h3>
                <p class="text-sm text-secondary mb-4">// {{ __('Use this to add a new informant or asset that is not a system user.') }}</p>
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
                    {{-- [BARU] Dropdown untuk Kategori Aset --}}
                    <div>
                        <label for="category" class="block text-primary text-sm">> {{ __('ASSET CATEGORY') }}</label>
                        <select id="category" name="category"
                            class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                            <option value=""> {{ __('-- Select Category --') }}</option>
                            @foreach($categories as $category)
                            <option class="text-black" value="{{ $category }}" {{ old('category')==$category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pembatas -->
            <div class="text-center text-secondary my-4 font-bold tracking-widest">{{ __('OR') }}</div>

            <!-- Mode 2: Hubungkan ke yang Sudah Ada -->
            <div>
                <h3 class="text-base text-primary font-bold mb-4">> [ {{ __('OPTION B: CONNECT TO EXISTING ENTITY') }} ]</h3>
                <p class="text-sm text-secondary mb-4">// {{ __('Use this to link to another registered operative or an existing asset.') }}</p>
                <div>
                    <label for="target_entity" class="block text-primary text-sm">> {{ __('SELECT TARGET') }}</label>
                    <select id="target_entity" name="target_entity"
                        class="mt-1 block w-full bg-base border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                        <option value=""> {{ __('-- Select Entity --') }}</option>
                        <optgroup class="text-green-800" label="{{ __('OPERATIVES (USERS)') }}">
                            @foreach($connectableUsers as $user)
                            <option class="text-black" value="user-{{ $user->id }}">{{ $user->codename }} ({{ $user->role->alias }})
                            </option>
                            @endforeach
                        </optgroup>
                        <optgroup class="text-green-800" label="{{ __('EXISTING ASSETS (FRIENDS)') }}">
                            @foreach($connectableFriends as $friend)
                            <option class="text-black" value="friend-{{ $friend->id }}">{{ $friend->codename }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            {{-- ====================================================== --}}
            {{-- AKHIR DARI BAGIAN YANG BARU --}}
            {{-- ====================================================== --}}

            <div class="border-t border-border-color pt-6 flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-primary text-black text-base transition-colors font-bold tracking-widest rounded-md cursor-pointer">
                    [ {{ __('ESTABLISH CONNECTION') }} ]
                </button>
            </div>
        </form>
    </div>
</x-app-layout>