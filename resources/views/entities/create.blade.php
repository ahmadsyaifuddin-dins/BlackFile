<x-app-layout title="Register New Entity">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-xl md:text-2xl font-bold text-primary font-mono tracking-wider">
            > REGISTER NEW ENTITY
        </h2>
        <x-button variant="outline" href="{{ route('entities.index') }}">
            &lt; ABORT MISSION
        </x-button>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
            <p class="font-bold">> Data Input Anomaly Detected:</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Form Container --}}
    <div class="bg-surface border border-border-color p-6">
        @include('entities._form', ['entity' => null])
    </div>
</x-app-layout>
