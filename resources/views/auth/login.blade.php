<x-guest-layout>
    <div x-data="{ loading: false, granted: false }" @submit.prevent="loading = true; setTimeout(() => { granted = true; $refs.form.submit(); }, 2000)">
        <div x-show="!loading" class="w-full">
            <h2 class="text-2xl text-primary font-bold mb-6 text-center">[ AGENT LOGIN ]</h2>
            <form x-ref="form" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label for="username" class="block text-primary">AGENT ID (USERNAME)</label>
                    <input id="username" class="block mt-1 w-full bg-surface border-border-color focus:border-primary focus:ring-primary" type="text" name="username" value="{{ old('username') }}" required autofocus />
                    @error('username')
                        <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-primary">PASSCODE</label>
                    <input id="password" class="block mt-1 w-full bg-surface border-border-color focus:border-primary focus:ring-primary" type="password" name="password" required />
                </div>
                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full px-4 py-2 bg-primary text-base text-base hover:bg-primary-hover font-bold tracking-widest">
                        > INITIATE CONNECTION
                    </button>
                </div>
            </form>
        </div>
        <div x-show="loading && !granted" class="text-center text-primary" x-transition><p class="text-2xl animate-pulse">ESTABLISHING SECURE LINK...</p></div>
        <div x-show="granted" class="text-center text-primary" x-transition><p class="text-4xl font-bold">[ ACCESS GRANTED ]</p></div>
    </div>
</x-guest-layout>