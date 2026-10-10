@php
    // Partial ini butuh variabel: $agents, $owner, $viewingOther
    $showSwitcher = isset($agents) && $agents->isNotEmpty();
@endphp

@if ($showSwitcher)
    <div class="mb-4 p-3 bg-surface border border-border-color rounded-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 font-mono">
        <div class="text-xs text-secondary">
            > {{ __('VIEWING FINANCE OF') }}:
            <span class="text-primary font-bold">
                {{ $viewingOther ? ($owner->codename ?? $owner->name) : __('MY ACCOUNT') }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <label for="finance-agent-switch" class="text-xs text-secondary whitespace-nowrap">> {{ __('AGENT') }}:</label>
            <select id="finance-agent-switch"
                onchange="window.location.href = this.value ? ('{{ url()->current() }}?agent=' + this.value) : '{{ url()->current() }}';"
                class="form-control text-sm py-1">
                <option value="">{{ __('-- My Account --') }}</option>
                @foreach ($agents as $agent)
                    @if ($agent->id === auth()->id())
                        @continue
                    @endif
                    <option value="{{ $agent->id }}" {{ $viewingOther && $agent->id === $owner->id ? 'selected' : '' }}>
                        {{ $agent->codename ?? $agent->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @if ($viewingOther)
        <div class="mb-4 p-3 bg-yellow-900/20 border-l-4 border-yellow-500 text-yellow-400 rounded-r-lg text-sm font-mono"
            role="alert">
            > {{ __('READ-ONLY MODE') }} — {{ __('You are viewing another agent\'s financial data. Editing is disabled.') }}
        </div>
    @endif
@endif
