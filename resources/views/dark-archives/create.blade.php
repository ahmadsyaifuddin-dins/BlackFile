<x-app-layout>
    <div class="min-h-screen bg-black text-gray-300 font-mono p-4 md:p-8">
        <div class="max-w-6xl mx-auto border-l-4 border-green-900 pl-4 md:pl-8 py-4">

            <!-- Header Dinamis: Berubah text tergantung Create atau Edit -->
            <div class="mb-10">
                <h1 class="text-xl md:text-4xl font-bold text-green-600 tracking-[0.2em] uppercase typing-effect mb-2">
                    @if ($archive)
                        > UPDATE CASE FILE: {{ $archive->case_code }}_
                    @else
                        > INPUT NEW CASE FILE_
                    @endif
                </h1>
                <p class="text-xs text-gray-500">
                    SECURE CONNECTION ESTABLISHED. ENCRYPTION: AES-256.
                    AGENT ID: {{ Auth::user()->name }}
                </p>
            </div>

            <!-- Include Form Partial -->
            <!-- Kita melempar variable $archive ke partial -->
            @include('dark-archives._form', ['archive' => $archive])

        </div>
    </div>
</x-app-layout>
