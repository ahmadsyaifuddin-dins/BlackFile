@props(['ranks'])

<div class="lg:col-span-1 border border-border-color border-gray-600 bg-surface p-4">
    <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> DATA CLASSIFICATION</h2>
    <div class="space-y-2 text-sm">
        @forelse($ranks as $rank)
            <p class="flex justify-between items-center">
                <span class="flex items-center truncate">
                    <span class="w-4 mr-1 text-secondary">></span>
                    <span class="text-white truncate" title="{{ $rank->rank ?? 'N/A' }}">{{ $rank->rank ?? 'N/A' }}</span>
                </span>
                <span class="text-white font-bold ml-2">{{ $rank->total }}</span>
            </p>
        @empty
            <p class="text-secondary">> No entity rank data available.</p>
        @endforelse
    </div>
</div>