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
        >
            <div x-show="status === 'idle'" x-transition>
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
                            password: $event.target.password.value
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
                        <input id="username" class="block mt-1 w-full bg-surface border-border-color focus:border-primary focus:ring-primary" type="text" name="username" value="{{ old('username') }}" required autofocus />
                    </div>
                    <div class="mt-4">
                        <label for="password" class="block text-primary">PASSCODE</label>
                        <input id="password" class="block mt-1 w-full bg-surface border-border-color focus:border-primary focus:ring-primary" type="password" name="password" required />
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="w-full px-4 py-2 bg-primary text-base text-base hover:bg-primary-hover font-bold tracking-widest cursor-pointer">
                            > INITIATE CONNECTION
                        </button>
                    </div>
                </form>
            </div>
    
            <div x-show="status === 'loading'" class="text-center text-primary" x-transition>
                <p class="text-2xl animate-pulse">ESTABLISHING SECURE LINK...</p>
            </div>
    
            <div x-show="status === 'granted'" class="text-center text-green-600" x-transition>
                <p class="text-2xl sm:text-4xl text- font-bold">[ ACCESS GRANTED ]</p>
            </div>
    
            <div x-show="status === 'denied'" class="text-center text-red-500" x-transition>
                <p class="text-2xl sm:text-4xl font-bold">[ ACCESS DENIED ]</p>
                <p class="mt-4 text-sm text-secondary" x-text="errorMessage"></p>
            </div>
        </div>
    </x-guest-layout>