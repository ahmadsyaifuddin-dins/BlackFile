<div class="bg-surface border border-gray-800">
    <h3 class="bg-gray-900 text-gray-400 text-xs font-bold px-4 py-2 border-b border-gray-800 uppercase tracking-widest">
        > IDENTITY METRICS</h3>
    <div class="p-4 space-y-3 text-sm">
        <div class="flex justify-between border-b border-gray-800 pb-2">
            <span class="text-gray-500">CATEGORY</span>
            <span class="text-white font-bold text-right">{{ $entity->category }}</span>
        </div>
        <div class="flex justify-between border-b border-gray-800 pb-2">
            <span class="text-gray-500">RANK</span>
            <span class="text-white font-bold text-right">{{ $entity->rank ?? 'N/A' }}</span>
        </div>
        <div class="flex justify-between border-b border-gray-800 pb-2">
            <span class="text-gray-500">ORIGIN</span>
            <span
                class="text-white font-bold text-right break-words max-w-[60%]">{{ $entity->origin ?? 'Unknown' }}</span>
        </div>
        <div class="flex justify-between items-center pt-1">
            <span class="text-gray-500">STATUS</span>
            <span
                class="px-2 py-0.5 text-[10px] border rounded-full 
                {{ $entity->status === 'ACTIVE'
                    ? 'border-green-500 text-green-500'
                    : ($entity->status === 'CONTAINED'
                        ? 'border-yellow-500 text-yellow-500'
                        : 'border-gray-500 text-gray-500') }}">
                {{ $entity->status }}
            </span>
        </div>
    </div>
</div>
