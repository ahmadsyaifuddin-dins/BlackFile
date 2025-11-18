@props(['agents'])

<div class="lg:col-span-2 border border-border-color border-gray-600 bg-surface p-4">
    <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> {{ __('AGENT NETWORK STATUS') }}</h2>
    <div class="space-y-3 text-sm">
        @forelse($agents as $agent)
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($agent->last_active_at->diffInMinutes(now()) < 5)
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-3 animate-pulse"></span>
                    @else
                        <span class="w-2 h-2 bg-gray-600 rounded-full mr-3"></span>
                    @endif
                    <span class="text-white">{{ $agent->codename }}</span>
                </div>
                <span class="text-xs text-gray-500">
                    {{ $agent->last_active_at->diffForHumans() }}
                </span>
            </div>
        @empty
            <p class="text-secondary">> {{ __('No other agent activity detected.') }}</p>
        @endforelse
    </div>
</div>