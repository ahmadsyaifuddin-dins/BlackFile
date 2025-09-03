<x-guest-layout>
    <h2 class="text-xl sm:text-2xl text-primary font-bold mb-6 text-center">[ REGISTER NEW DOSSIER ]</h2>

    {{-- [LOGIKA BARU] Cek apakah ada sesi 'pending_approval' --}}
    @if(session('pending_approval'))
        {{-- Tampilan setelah berhasil mendaftar tanpa token --}}
        <div class="text-center text-glow space-y-4">
            <p class="text-primary-hover font-bold">> APPLICATION SUBMITTED</p>
            <p class="text-secondary text-sm">
                Your dossier has been successfully submitted. It is now awaiting review and confirmation from the Directorate. 
                You will be notified via email upon approval.
            </p>
            <div class="pt-4">
                <a href="{{ route('welcome') }}" class="px-4 py-2 bg-surface border border-border-color text-secondary hover:text-white font-bold tracking-widest rounded-md text-sm">
                    [ RETURN TO WELCOME PAGE ]
                </a>
            </div>
        </div>
    @else
        {{-- Tampilan form registrasi (jika tidak ada sesi 'pending_approval') --}}
        
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

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-primary text-sm">> REAL NAME</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-primary text-sm">> USERNAME</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                </div>
                <div>
                    <label for="codename" class="block text-primary text-sm">> CODENAME</label>
                    <input type="text" id="codename" name="codename" value="{{ old('codename') }}" required class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                </div>
            </div>
            <div>
                <label for="email" class="block text-primary text-sm">> EMAIL</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-primary text-sm">> PASSCODE</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-primary text-sm">> CONFIRM PASSCODE</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
                </div>
            </div>
            <div>
                <label for="invite_code" class="block text-primary text-sm">> INVITE TOKEN (Optional)</label>
                <input type="text" id="invite_code" name="invite_code" value="{{ old('invite_code', $inviteCode) }}" class="mt-1 block w-full bg-surface border-2 border-border-color focus:border-primary focus:ring-primary text-secondary p-2 rounded">
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full px-6 py-2 bg-black text-primary text-base hover:bg-primary-hover transition-colors font-bold tracking-widest rounded-md text-sm">
                    [ SUBMIT APPLICATION ]
                </button>
            </div>
        </form>
        <p class="text-xs text-secondary mt-2 text-center">Already have an account? <a href="{{ route('login') }}" class="text-primary hover:underline">Login</a></p>
    @endif
</x-guest-layout>