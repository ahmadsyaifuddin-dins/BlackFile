<x-app-layout title="Register New Entity">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-2xl font-bold text-primary font-mono tracking-wider">
            > REGISTER NEW ENTITY
        </h2>
        <a href="{{ route('entities.index') }}"
            class="w-full sm:w-auto text-center bg-surface-light border border-border-color text-secondary font-bold py-2 px-4 hover:text-primary hover:border-primary transition-colors">
            &lt; ABORT MISSION
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-surface border border-border-color p-6">
        @include('entities._form', ['entity' => null])
    </div>
</x-app-layout>