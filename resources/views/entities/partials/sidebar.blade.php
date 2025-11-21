<div class="lg:col-span-4 space-y-6">

    {{-- 1. PROFILE IMAGE (MUGSHOT) --}}
    @include('entities.partials.profile-image', ['mainImage' => $mainImage])

    {{-- 2. IDENTITY DATA (TABLE) --}}
    @include('entities.partials.identity-metrics', ['entity' => $entity])

    {{-- 3. RADAR CHART --}}
    @if ($entity->combat_stats)
        @include('entities.partials.combat-chart', ['entity' => $entity])
    @endif

    {{-- 4. DELETE BUTTON --}}
    <div class="text-center">
        <x-button.delete :action="route('entities.destroy', $entity)" title="TERMINATE ENTITY?"
            message="WARNING: This action is irreversible. Confirm deletion?" target="{{ $entity->name }}"
            class="w-full p-3 justify-center bg-red-900/20 hover:bg-red-900/50 text-red-500 border-red-900/50">
            [ TERMINATE RECORD ]
        </x-button.delete>
    </div>

</div>
