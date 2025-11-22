<x-app-layout title="Conflict Simulation">
    {{-- Init Alpine dengan passing Data PHP ke JS --}}
    <div x-data='battleSystem(@json($entities), "{{ route('battle.run') }}", "{{ csrf_token() }}")'
        class="min-h-screen text-gray-100 font-mono p-4 lg:p-8 relative overflow-hidden">

        {{-- 1. Background --}}
        <x-battle.background />

        {{-- 2. Header --}}
        <x-battle.header />

        {{-- 3. Battle Grid Area --}}
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto">

            {{-- Left: Alpha Card --}}
            <div class="lg:col-span-4">
                <x-battle.card side="left" color="green" label="SUBJECT ALPHA" modelId="attackerId" modelData="attacker"
                    :entities="$entities" chartId="chartAlpha" />
            </div>

            {{-- Center: Controls --}}
            <x-battle.controls />

            {{-- Right: Omega Card --}}
            <div class="lg:col-span-4">
                <x-battle.card side="right" color="red" label="SUBJECT OMEGA" modelId="defenderId"
                    modelData="defender" :entities="$entities" chartId="chartOmega" />
            </div>
        </div>

        {{-- 4. Terminal & History Area --}}
        <div class="relative z-10 mt-8 max-w-7xl mx-auto grid grid-cols-1 gap-8">
            <x-battle.terminal />
            <x-battle.history :history="$history" />
        </div>

    </div>
</x-app-layout>
