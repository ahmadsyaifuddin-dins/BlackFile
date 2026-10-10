<x-app-layout>
    <x-slot:title>
        {{ __('FINANCE // TRANSACTIONS') }}
    </x-slot:title>

    @include('finance.partials.context')

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
        <h2 class="text-lg md:text-xl font-bold text-primary text-glow font-mono">
            > [ {{ __('CASH FLOW TERMINAL') }} ]
        </h2>

        @unless ($readOnly)
            <x-button href="{{ route('finance.transactions.create') }}" class="justify-center">
                [ + {{ __('NEW TRANSACTION') }} ]
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
    @if (session('warning'))
        <div class="mb-4 bg-yellow-900/30 border-l-4 border-yellow-500 text-yellow-300 px-4 py-3 rounded-r-lg font-mono text-sm">
            > {{ session('warning') }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 font-mono">
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <p class="text-xs text-secondary uppercase tracking-widest mb-1">> {{ __('Total Balance') }}</p>
            <p class="text-lg sm:text-2xl font-bold text-primary">{{ \App\Support\Money::idr($totalBalance) }}</p>
        </div>
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <p class="text-xs text-secondary uppercase tracking-widest mb-1">> {{ __('Income (This Month)') }}</p>
            <p class="text-lg sm:text-2xl font-bold text-green-400">+ {{ \App\Support\Money::idr($monthIncome) }}</p>
        </div>
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <p class="text-xs text-secondary uppercase tracking-widest mb-1">> {{ __('Expense (This Month)') }}</p>
            <p class="text-lg sm:text-2xl font-bold text-red-400">- {{ \App\Support\Money::idr($monthExpense) }}</p>
        </div>
        <div class="bg-surface border border-border-color rounded-lg p-4">
            <p class="text-xs text-secondary uppercase tracking-widest mb-1">> {{ __('Outstanding Receivables') }}</p>
            <p class="text-lg sm:text-2xl font-bold text-yellow-400">{{ \App\Support\Money::idr($outstandingReceivable) }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('finance.transactions.index') }}" class="mb-4 p-4 bg-surface border border-border-color rounded-lg">
        @if ($viewingOther)
            <input type="hidden" name="agent" value="{{ $ownerId }}">
        @endif
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-primary text-sm mb-1 font-mono">> {{ __('SEARCH') }}</label>
                <x-forms.input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Description / category') }}" />
            </div>
            <div>
                <label class="block text-primary text-sm mb-1 font-mono">> {{ __('TYPE') }}</label>
                <select name="type" class="form-control cursor-pointer">
                    <option value="">{{ __('All') }}</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>{{ __('Cash In') }}</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>{{ __('Cash Out') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-primary text-sm mb-1 font-mono">> {{ __('FUND SOURCE') }}</label>
                <select name="source" class="form-control cursor-pointer">
                    <option value="">{{ __('All') }}</option>
                    @foreach ($sources as $source)
                        <option value="{{ $source->id }}" {{ (string) request('source') === (string) $source->id ? 'selected' : '' }}>
                            {{ $source->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <x-button type="submit" class="flex-1 justify-center">[ {{ __('FILTER') }} ]</x-button>
                <x-button variant="outline" href="{{ route('finance.transactions.index', array_filter(['agent' => $viewingOther ? $ownerId : null])) }}" class="justify-center">
                    {{ __('RESET') }}
                </x-button>
            </div>
        </div>
    </form>

    {{-- Tabel --}}
    <div class="bg-surface border border-border-color rounded-lg p-4 font-mono">
        <h3 class="text-base font-bold text-primary border-b border-border-color pb-2 mb-3">> {{ __('TRANSACTION LOG') }}</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b-2 border-border-color text-secondary">
                    <tr>
                        <th class="p-3 whitespace-nowrap">{{ __('DATE') }}</th>
                        <th class="p-3">{{ __('DESCRIPTION') }}</th>
                        <th class="p-3">{{ __('CATEGORY') }}</th>
                        <th class="p-3">{{ __('SOURCE') }}</th>
                        <th class="p-3 text-right">{{ __('AMOUNT') }}</th>
                        @unless ($readOnly)
                            <th class="p-3 text-right">{{ __('ACTIONS') }}</th>
                        @endunless
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="border-b border-border-color hover:bg-white/5">
                            <td class="p-3 whitespace-nowrap text-secondary">
                                {{ $transaction->transacted_at?->format('d-m-Y H:i') }}
                            </td>
                            <td class="p-3 text-white">
                                {{ $transaction->description ?: '—' }}
                                @if ($transaction->receivable_id)
                                    <span class="ml-1 text-[10px] px-1.5 py-0.5 border border-primary/40 text-primary rounded">{{ __('AUTO') }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-secondary whitespace-nowrap">
                                @if ($transaction->category)
                                    <span class="inline-flex items-center gap-1.5 text-white">
                                        <span class="text-primary"><x-finance.category-icon :category="$transaction->category" :type="$transaction->type" /></span>
                                        {{ $transaction->category }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="p-3 whitespace-nowrap">{{ $transaction->fundSource->name ?? '—' }}</td>
                            <td class="p-3 text-right whitespace-nowrap font-bold {{ $transaction->type === 'in' ? 'text-green-400' : 'text-red-400' }}">
                                {{ $transaction->type === 'in' ? '+' : '-' }} {{ \App\Support\Money::idr($transaction->amount) }}
                            </td>
                            @unless ($readOnly)
                                <td class="p-3 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-4">
                                        <a href="{{ route('finance.transactions.edit', $transaction) }}"
                                            class="text-secondary hover:text-primary text-sm">{{ __('EDIT') }}</a>
                                        <x-button.delete :action="route('finance.transactions.destroy', $transaction)"
                                            title="{{ __('DELETE TRANSACTION?') }}"
                                            message="{{ __('This transaction will be permanently removed and balances will change.') }}"
                                            :target="$transaction->description ?: $transaction->category">
                                            > {{ __('DELETE') }}
                                        </x-button.delete>
                                    </div>
                                </td>
                            @endunless
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $readOnly ? 5 : 6 }}" class="p-6 text-center text-secondary">
                                {{ __('No transactions recorded yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
