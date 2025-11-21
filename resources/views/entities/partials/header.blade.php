<div
    class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-gray-800 pb-6 mb-8 gap-4">
    <div>
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <span class="text-xs font-bold tracking-[0.2em] text-gray-500">CLASSIFIED RECORD //
                {{ $entity->id }}</span>
            @if ($entity->power_tier)
                <span
                    class="px-2 py-0.5 text-[10px] font-bold border {{ $entity->power_tier <= 2 ? 'border-red-500 text-red-500 bg-red-500/10' : ($entity->power_tier <= 5 ? 'border-yellow-500 text-yellow-500 bg-yellow-500/10' : 'border-green-500 text-green-500 bg-green-500/10') }}">
                    TIER {{ $entity->power_tier }}
                </span>
            @endif
            @if ($entity->combat_type)
                <span
                    class="px-2 py-0.5 text-[10px] font-bold border {{ $entity->combat_type === 'HAZARD' ? 'border-purple-500 text-purple-500 bg-purple-500/10' : 'border-blue-500 text-blue-500 bg-blue-500/10' }}">
                    {{ $entity->combat_type }}
                </span>
            @endif
        </div>
        <h1 class="text-3xl md:text-5xl font-black text-white tracking-wide uppercase drop-shadow-md">
            {{ $entity->codename ?? $entity->name }}
        </h1>
        <p class="text-primary text-sm mt-1 tracking-widest">CODENAME: {{ $entity->name }}</p>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="flex flex-wrap gap-2">
        <x-button href="{{ route('entities.assessment', $entity) }}"
            class="bg-yellow-600 hover:bg-yellow-500 text-black border-yellow-500 font-bold text-xs px-4">
            > TACTICAL ASSESSMENT
        </x-button>
        <x-button variant="outline" href="{{ $editUrl }}" class="text-xs">EDIT DATA</x-button>
        <x-button variant="outline" href="{{ $backToIndex }}" class="text-xs">CLOSE FILE</x-button>
    </div>
</div>
