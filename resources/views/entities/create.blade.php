<x-app-layout title="Register New Entity">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-2xl font-bold text-primary font-mono tracking-wider">
            > REGISTER NEW ENTITY
        </h2>
        <x-button variant="outline" href="{{ route('entities.index') }}">
            &lt; ABORT MISSION
        </x-button>
    </div>

    {{-- Form Container --}}
    <div class="bg-surface border border-border-color p-6">
        @include('entities._form', ['entity' => null])
    </div>
</x-app-layout>