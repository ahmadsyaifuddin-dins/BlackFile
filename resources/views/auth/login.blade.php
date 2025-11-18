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
        {{-- Jadikan container ini 'relative' untuk menjadi jangkar bagi animasi --}}
        class="relative min-h-[250px]"
    >
        <!-- Form Login -->
        {{-- Form sekarang menjadi transparan saat status berubah, bukan menghilang --}}
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
                    <label for="username" class="block text-primary text-sm mb-1">AGENT ID (USERNAME)</label>
                    <x-forms.input id="username" type="text" name="username" :value="old('username')" required autofocus>
                        <x-slot:icon>
                            <i class="fas fa-user-secret text-gray-500"></i>
                        </x-slot:icon>
                    </x-forms.input>
                </div>
                
                <div class="mt-4">
                    <label for="password" class="block text-primary text-sm mb-1">PASSCODE</label>
                    <x-forms.input id="password" type="password" name="password" required>
                        <x-slot:icon>
                            <i class="fas fa-lock text-gray-500"></i>
                        </x-slot:icon>
                    </x-forms.input>
                </div>
                
                <div class="block mt-4">
                    <x-forms.checkbox id="remember_me" name="remember">
                        Remember me
                    </x-forms.checkbox>
                </div>
                
                <div class="flex items-center justify-end mt-6">
                    <x-button type="submit" class="w-full justify-center">
                        > INITIATE CONNECTION
                    </x-button>
                </div>
            </form>
            <p class="text-xs text-secondary mt-2">Don't have an account? <a href="{{ route('register') }}" class="text-primary hover:underline">Register</a></p>
        </div>

        <!-- Container untuk semua animasi, diposisikan absolut di tengah -->
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