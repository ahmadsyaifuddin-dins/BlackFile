@props(['activities'])

<div class="lg:col-span-2 border border-border-color border-gray-600 bg-surface p-4">
    <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> {{ __('RECENT ACTIVITY LOG') }}</h2>
    <div class="space-y-3 text-sm">
        @forelse($activities as $activity)
            <div class="flex justify-between items-baseline">
                <p class="truncate">
                    <span class="text-secondary mr-2"> > {{ __('Entities updated:') }}</span>
                    <a href="{{ route('entities.show', $activity) }}" class="text-white hover:text-primary transition-colors">
                        {{ $activity->codename ?? $activity->name }}
                    </a>
                </p>
                <span class="text-xs text-gray-500 flex-shrink-0 ml-4">
                    {{ $activity->updated_at->diffForHumans() }}
                </span>
            </div>
        @empty
            <p class="text-secondary">> {{ __('No recent activity in the entity database.') }}</p>
        @endforelse
    </div>
</div>