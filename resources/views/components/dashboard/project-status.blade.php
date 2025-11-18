@props(['statuses', 'total'])

<div class="lg:col-span-2 border border-border-color border-gray-600 bg-surface p-4">
    <h2 class="text-lg font-bold text-primary border-b border-border-color pb-2 mb-3">> {{ __('PROJECT STATUS OVERVIEW') }}</h2>
    <div class="space-y-4 text-sm">
        @forelse($statuses as $status)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-white">{{ $status->status }}</span>
                    <span class="text-secondary">{{ $status->total }} / {{ $total }}</span>
                </div>
                <div class="w-full bg-base border border-border-color h-4">
                    @php
                        $percentage = $total > 0 ? ($status->total / $total) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-secondary">> {{ __('No project data available to display.') }}</p>
        @endforelse
    </div>
</div>