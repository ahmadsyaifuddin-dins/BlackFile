<x-app-layout>
    <x-slot:title>
        {{ __('FINANCE // FUND SOURCES') }}
    </x-slot:title>

    @include('finance.partials.context')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        <h2 class="text-lg md:text-xl font-bold text-primary text-glow font-mono">
            > [ {{ __('FUND SOURCES') }} ]
        </h2>

        @unless ($readOnly)
            <x-button href="{{ route('finance.fund-sources.create') }}" class="justify-center">
                [ + {{ __('NEW FUND SOURCE') }} ]
            </x-button>
        @endunless
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-900/40 border-l-4 border-primary text-primary px-4 py-3 rounded-r-lg font-mono text-sm">
            > {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 bg-red-900/40 border-l-4 border-red-500 text-red-300 px-4 py-3 rounded-r-lg font-mono text-sm">
            > {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 p-4 bg-surface border border-border-color rounded-lg font-mono flex items-center justify-between">
        <span class="text-secondary text-sm">> {{ __('TOTAL BALANCE ACROSS ALL SOURCES') }}</span>
        <span class="text-xl sm:text-2xl font-bold text-primary">{{ \App\Support\Money::idr($totalBalance) }}</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-mono">
        @forelse ($sources as $source)
            <div class="bg-surface border border-border-color rounded-lg p-4 flex flex-col">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="text-primary font-bold text-lg break-words">{{ $source->name }}</h3>
                        @if ($source->description)
                            <p class="text-secondary text-xs mt-1 break-words">{{ $source->description }}</p>
                        @endif
                    </div>
                    @unless ($readOnly)
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="{{ route('finance.fund-sources.edit', $source) }}"
                                class="text-secondary hover:text-primary text-xs">{{ __('EDIT') }}</a>
                            <x-button.delete :action="route('finance.fund-sources.destroy', $source)"
                                title="{{ __('DELETE FUND SOURCE?') }}"
                                message="{{ __('This fund source will be permanently removed. It can only be deleted when it has no transactions or receivables.') }}"
                                :target="$source->name">> {{ __('DEL') }}</x-button.delete>
                        </div>
                    @endunless
                </div>

                <div class="mt-4 pt-3 border-t border-border-color">
                    <p class="text-[11px] text-secondary uppercase tracking-widest">{{ __('CURRENT BALANCE') }}</p>
                    <p class="text-2xl font-bold {{ $source->current_balance < 0 ? 'text-red-400' : 'text-white' }}">
                        {{ \App\Support\Money::idr($source->current_balance) }}
                    </p>
                    <p class="text-xs text-secondary mt-1">> {{ __('Initial') }}: {{ \App\Support\Money::idr($source->initial_balance) }}</p>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 p-8 text-center bg-surface border border-border-color rounded-lg text-secondary font-mono">
                {{ __('No fund sources yet.') }}
                @unless ($readOnly)
                    <a href="{{ route('finance.fund-sources.create') }}" class="text-primary hover:text-white"> {{ __('Create your first one') }} &rarr;</a>
                @endunless
            </div>
        @endforelse
    </div>
</x-app-layout>
