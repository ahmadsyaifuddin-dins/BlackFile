<x-app-layout title="Set Up Master Password">
    {{-- Header Halaman --}}
    <div class="border-y-2 border-dashed border-primary/50 py-4 mb-8">
        <h1 class="text-4xl font-bold text-primary tracking-widest font-mono text-glow">
            > VAULT SECURITY SETUP
        </h1>
        <p class="text-sm text-secondary font-mono mt-1">A Master Password is required to initialize the encrypted contact vault.</p>
    </div>

    <div class="max-w-xl mx-auto bg-surface border border-border-color p-6 font-mono">
        <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-4">> CREATE MASTER PASSWORD</h2>
        
        <div class="mb-4 bg-yellow-900/20 border border-yellow-500/50 text-yellow-300 text-sm p-4">
            <p><span class="font-bold">CRITICAL WARNING:</span> Password Master ini mengenkripsi data kontak Anda. Jika Anda lupa kata sandi ini, data kontak Anda akan <span class="font-bold">HILANG SELAMANYA</span> dan tidak dapat dipulihkan. Tidak ada opsi “Lupa Kata Sandi”. Simpan kata sandi ini dengan aman.</p>
        </div>

        <form action="{{ route('master-password.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="master_password" class="block text-sm text-secondary mb-2">New Master Password:</label>
                <input type="password" name="master_password" id="master_password" required
                       class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
                @error('master_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="master_password_confirmation" class="block text-sm text-secondary mb-2">Confirm Master Password:</label>
                <input type="password" name="master_password_confirmation" id="master_password_confirmation" required
                       class="w-full bg-base border-2 border-border-color focus:border-primary focus:ring-0 text-white p-2">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary text-black font-bold py-3 px-8 hover:bg-primary-hover transition-colors tracking-widest cursor-pointer">
                    INITIALIZE VAULT
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
