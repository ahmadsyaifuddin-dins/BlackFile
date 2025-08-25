<x-guest-layout>
    <div 
        x-data="{ 
            status: 'idle', // 'idle', 'loading', 'granted', 'denied'
            errorMessage: ''
        }"
        x-init="$watch('status', value => {
            if (value === 'granted' || value === 'denied') {
                setTimeout(() => { status = 'idle' }, 3000)
            }
        })"
        {{-- [DIUBAH] Jadikan container ini 'relative' untuk menjadi jangkar bagi animasi --}}
        class="relative min-h-[250px]"
    >
        <!-- Form Login -->
        {{-- [DIUBAH] Form sekarang menjadi transparan saat status berubah, bukan menghilang --}}
        <div 
            :class="{ 'opacity-0 invisible': status !== 'idle' }"
            class="transition-opacity duration-300"
            x-cloak
        >
            <h2 class="text-2xl text-primary font-bold mb-6 text-center">[ AGENT LOGIN ]</h2>
            <form @submit.prevent="
                status = 'loading';
                fetch('{{ route('login') }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({
                        username: $event.target.username.value,
                        password: $event.target.password.value,
                        remember: $event.target.remember.checked
                    })
                })
                .then(response => response.json().then(data => ({status: response.status, body: data})))
                .then(result => {
                    if (result.status === 200) {
                        status = 'granted';
                        setTimeout(() => window.location.href = result.body.redirect, 1000);
                    } else {
                        status = 'denied';
                        errorMessage = result.body.message;
                    }
                })
                .catch(() => {
                    status = 'denied';
                    errorMessage = 'A connection error occurred.';
                })
            ">
                @csrf
                <div>
                    <label for="username" class="block text-primary">AGENT ID (USERNAME)</label>
                    <input id="username"
                        class="block mt-1 w-full bg-surface border-border-color focus:border-primary focus:ring-primary"
                        type="text" name="username" value="{{ old('username') }}" required autofocus />
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-primary">PASSCODE</label>
                    <input id="password"
                        class="block mt-1 w-full bg-surface border-border-color focus:border-primary focus:ring-primary"
                        type="password" name="password" required />
                </div>
                
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center cursor-pointer">
                        <input id="remember" type="checkbox" name="remember" 
                               class="rounded bg-surface border-border-color text-primary shadow-sm focus:ring-primary focus:ring-offset-surface">
                        <span class="ms-2 text-sm text-secondary hover:text-white">> Keep me logged in</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit"
                        class="w-full px-4 py-2 text-primary text-base hover:bg-primary-hover font-bold tracking-widest cursor-pointer">
                        > INITIATE CONNECTION
                    </button>
                </div>
            </form>
        </div>

        <!-- [DIUBAH] Container untuk semua animasi, diposisikan absolut di tengah -->
        <div x-show="status !== 'idle'" x-transition x-cloak 
             class="absolute inset-0 flex items-center justify-center">
            
            <!-- Animasi Loading -->
            <div x-show="status === 'loading'" class="text-center text-primary">
                <p class="text-2xl animate-pulse">ESTABLISHING SECURE LINK...</p>
            </div>
    
            <!-- Animasi Granted -->
            <div x-show="status === 'granted'" class="text-center text-green-600">
                <p class="text-2xl sm:text-4xl font-bold">[ ACCESS GRANTED ]</p>
            </div>
    
            <!-- Animasi Denied -->
            <div x-show="status === 'denied'" class="text-center text-red-500">
                <p class="text-2xl sm:text-4xl font-bold">[ ACCESS DENIED ]</p>
                <p class="mt-4 text-sm text-secondary" x-text="errorMessage"></p>
            </div>
        </div>
    </div>
</x-guest-layout>