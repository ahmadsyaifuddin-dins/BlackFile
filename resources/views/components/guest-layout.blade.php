<x-layout>
    {{-- Mengatur judul spesifik untuk layout ini --}}
    <x-slot:title>
        AUTHENTICATION
    </x-slot:title>

    {{-- Ini adalah konten asli dari guest-layout, tanpa tag html, head, dan body --}}
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            {{-- Menggunakan named route lebih baik daripada hardcoded '/' --}}
            <a href="{{ route('welcome') }}">
                <h1 class="text-5xl font-bold text-primary">[B.F]</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-surface border-2 border-border-color shadow-md overflow-hidden sm:rounded-lg">
            {{-- Di sinilah konten dari login.blade.php akan dimasukkan --}}
            {{ $slot }}
        </div>
    </div>
</x-layout>