<x-app-layout>
    <x-slot:title>
        {{ __('FINANCE // RECEIVABLES') }}
    </x-slot:title>

    @include('finance.partials.context')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        <h2 class="text-lg md:text-xl font-bold text-primary text-glow font-mono">
            > [ {{ __('RECEIVABLES') }} ]
        </h2>

        @unless ($readOnly)
            <x-button href="{{ route('finance.receivables.create') }}" class="justify-center">
                [ + {{ __('NEW RECEIVABLE') }} ]
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

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 font-mono">
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <p class="text-xs text-secondary uppercase tracking-widest mb-1">> {{ __('Outstanding (Unpaid)') }}</p>
            <p class="text-lg sm:text-2xl font-bold text-yellow-400">{{ \App\Support\Money::idr($unpaidTotal) }}</p>
        </div>
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <p class="text-xs text-secondary uppercase tracking-widest mb-1">> {{ __('Settled (Paid)') }}</p>
            <p class="text-lg sm:text-2xl font-bold text-green-400">{{ \App\Support\Money::idr($paidTotal) }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('finance.receivables.index') }}" class="mb-4 p-4 bg-surface border border-border-color rounded-lg">
        @if ($viewingOther)
            <input type="hidden" name="agent" value="{{ $ownerId }}">
        @endif
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-primary text-sm mb-1 font-mono">> {{ __('SEARCH') }}</label>
                <x-forms.input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Debtor / description') }}" />
            </div>
            <div>
                <label class="block text-primary text-sm mb-1 font-mono">> {{ __('STATUS') }}</label>
                <select name="status" class="form-control cursor-pointer">
                    <option value="">{{ __('All') }}</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-button type="submit" class="flex-1 justify-center">[ {{ __('FILTER') }} ]</x-button>
                <x-button variant="outline" href="{{ route('finance.receivables.index', array_filter(['agent' => $viewingOther ? $ownerId : null])) }}" class="justify-center">
                    {{ __('RESET') }}
                </x-button>
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-surface border border-border-color rounded-lg p-4 font-mono">
        <h3 class="text-base font-bold text-primary border-b border-border-color pb-2 mb-3">> {{ __('RECEIVABLE LEDGER') }}</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b-2 border-border-color text-secondary">
                    <tr>
                        <th class="p-3">{{ __('DEBTOR') }}</th>
                        <th class="p-3 whitespace-nowrap">{{ __('DATE') }}</th>
                        <th class="p-3">{{ __('SOURCE') }}</th>
                        <th class="p-3 whitespace-nowrap">{{ __('STATUS') }}</th>
                        <th class="p-3 text-right">{{ __('AMOUNT') }}</th>
                        @unless ($readOnly)
                            <th class="p-3 text-right">{{ __('ACTIONS') }}</th>
                        @endunless
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receivables as $receivable)
                        <tr class="border-b border-border-color hover:bg-white/5">
                            <td class="p-3">
                                <span class="text-white font-bold">{{ $receivable->debtor_name }}</span>
                                @if ($receivable->is_legacy)
                                    <span class="ml-1 text-[10px] px-1.5 py-0.5 border border-purple-500/40 text-purple-300 rounded">{{ __('PAST') }}</span>
                                @endif
                                @if ($receivable->description)
                                    <span class="block text-xs text-secondary mt-0.5">{{ $receivable->description }}</span>
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap text-secondary">{{ $receivable->transacted_at?->format('d-m-Y H:i') }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $receivable->fundSource->name ?? '—' }}</td>
                            <td class="p-3 whitespace-nowrap">
                                @if ($receivable->isPaid())
                                    <span class="text-green-400 font-bold">> {{ __('SETTLED') }}</span>
                                    @if ($receivable->paid_at)
                                        <span class="block text-[11px] text-secondary">{{ $receivable->paid_at->format('d-m-Y H:i') }}</span>
                                    @endif
                                @else
                                    <span class="text-yellow-400 font-bold">> {{ __('UNPAID') }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-right whitespace-nowrap font-bold text-white">{{ \App\Support\Money::idr($receivable->amount) }}</td>
                            @unless ($readOnly)
                                <td class="p-3 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-3">
                                        @if ($receivable->isPaid())
                                            <form method="POST" action="{{ route('finance.receivables.unsettle', $receivable) }}"
                                                x-data @submit.prevent="window.agentConfirm('{{ __('REVERT SETTLEMENT?') }}', '{{ __('This will remove the cash-in transaction and mark the receivable as unpaid.') }}', '{{ __('REVERT') }}', '{{ __('CANCEL') }}').then(ok => { if (ok) $el.submit(); })">
                                                @csrf
                                                <button type="submit" class="text-secondary hover:text-primary text-sm font-bold">{{ __('UNSETTLE') }}</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('finance.receivables.settle', $receivable) }}"
                                                x-data @submit.prevent="window.agentConfirm('{{ __('MARK AS SETTLED?') }}', '{{ __('This will record a cash-in transaction to the selected fund source.') }}', '{{ __('SETTLE') }}', '{{ __('CANCEL') }}').then(ok => { if (ok) $el.submit(); })">
                                                @csrf
                                                <button type="submit" class="text-primary hover:text-white text-sm font-bold">{{ __('SETTLE') }}</button>
                                            </form>
                                        @endif

                                        <a href="{{ route('finance.receivables.edit', $receivable) }}"
                                            class="text-secondary hover:text-primary text-sm">{{ __('EDIT') }}</a>

                                        <x-button.delete :action="route('finance.receivables.destroy', $receivable)"
                                            title="{{ __('DELETE RECEIVABLE?') }}"
                                            message="{{ __('This receivable and its auto-generated transactions will be permanently removed.') }}"
                                            :target="$receivable->debtor_name">> {{ __('DEL') }}</x-button.delete>
                                    </div>
                                </td>
                            @endunless
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $readOnly ? 5 : 6 }}" class="p-6 text-center text-secondary">
                                {{ __('No receivables recorded yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $receivables->links() }}
        </div>
    </div>
</x-app-layout>
