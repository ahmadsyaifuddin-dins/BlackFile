<div x-data='entityChart(@json($entity->combat_stats))' class="bg-black border border-gray-800 p-4 relative">
    <div class="absolute top-0 right-0 bg-gray-900 text-primary text-[10px] font-bold px-2 py-1">
        COMBAT_METRICS</div>
    <div class="relative h-56 w-full mt-4">
        <canvas id="tacticalChart"></canvas>
    </div>
</div>
