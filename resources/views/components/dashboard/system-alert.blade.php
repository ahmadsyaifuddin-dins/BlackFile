@props(['alert'])

<div class="lg:col-span-1 border-2 border-yellow-500/50 bg-yellow-900/20 p-4 animate-pulse">
    <h2 class="text-lg font-bold text-yellow-400 border-b border-yellow-500/30 pb-2 mb-3">> {{ __('SYSTEM ALERT') }}</h2>
    @if($alert)
        <p class="text-yellow-300 text-sm">{{ __('High-threat entity detected') }}:</p>
        <a href="{{ route('entities.show', $alert) }}" class="block mt-1 text-white font-bold text-md hover:underline">
            {{ $alert->codename ?? $alert->name }}
        </a>
        <p class="text-xs text-yellow-400/70">{{ __('Classification') }}: {{ $alert->rank }}</p>
    @else
        <p class="text-yellow-300 text-sm">> All systems clear. No immediate high-threat entities detected.</p>
    @endif
</div>