<x-app-layout>
    <x-slot:title>
        Create Your End Credits
    </x-slot:title>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Your End Credits') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Pastikan form ini ada -->
                <form action="{{ route('credits.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @php
                        // Untuk halaman create, kita mulai dengan data kosong
                        $formattedCredits = collect([]); 
                        $musicPath = null;
                    @endphp

                    @include('credits._form_dynamic', [
                        'credits' => $formattedCredits->toJson(),
                        'musicPath' => $musicPath
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
