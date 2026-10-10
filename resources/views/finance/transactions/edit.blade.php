<x-app-layout>
    <x-slot:title>
        {{ __('FINANCE // EDIT TRANSACTION') }}
    </x-slot:title>

    @php
        $sourceOptions = $sources->pluck('name', 'id')->all();
        $customSentinel = $categoryCustom ?? \App\Http\Controllers\Finance\CashTransactionController::CUSTOM_CATEGORY;
        $selectedType = old('type', $transaction->type);
        $storedCategory = old('category', $transaction->category);
        $knownCategories = $knownCategories ?? array_merge($incomeCategories, $expenseCategories);
        $isCustomCategory = $storedCategory !== '' && $storedCategory !== null && ! in_array($storedCategory, $knownCategories, true);
        $selectedCategory = $isCustomCategory ? $customSentinel : ($storedCategory ?? '');
        $customCategoryName = $isCustomCategory ? $storedCategory : '';
    @endphp

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg font-mono">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="text-lg sm:text-2xl font-bold text-primary">
                > [ {{ __('EDIT CASH TRANSACTION') }} ]
            </h2>
            <x-button variant="outline"
                href="{{ route('finance.transactions.index', array_filter(['agent' => $viewingOther ? $ownerId : null])) }}"
                class="justify-center">
                &lt; {{ __('Back') }}
            </x-button>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg text-sm" role="alert">
                <p class="font-bold mb-2">> {{ __('Data Input Anomaly Detected') }}:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('finance.transactions.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-forms.select label="> {{ __('TRANSACTION TYPE') }}" name="type"
                    :options="['in' => __('CASH IN'), 'out' => __('CASH OUT')]"
                    :selected="old('type', $transaction->type)" :searchable="false" />

                <div>
                    <label for="amount" class="block text-primary text-sm mb-1">> {{ __('AMOUNT (IDR)') }}</label>
                    <x-forms.money id="amount" name="amount" :value="old('amount', (float) $transaction->amount)"
                        placeholder="0" />
                </div>

                <x-forms.select label="> {{ __('FUND SOURCE') }}" name="fund_source_id"
                    :options="$sourceOptions" :selected="old('fund_source_id', $transaction->fund_source_id)"
                    placeholder="{{ __('-- Select Fund Source --') }}" :searchable="true" />

                <div>
                    <label for="transaction-category" class="block text-primary text-sm mb-1">> {{ __('CATEGORY (OPTIONAL)') }}</label>
                    <x-finance.category-select id="transaction-category"
                        :income="$incomeCategories" :expense="$expenseCategories"
                        :selected="$selectedCategory" :custom-name="$customCategoryName"
                        :default-in="$defaultIncome" :default-out="$defaultExpense"
                        :type="$selectedType" />
                </div>

                <div>
                    <label for="transacted_at" class="block text-primary text-sm mb-1">> {{ __('DATE & TIME') }}</label>
                    <x-forms.input type="datetime-local" id="transacted_at" name="transacted_at"
                        value="{{ old('transacted_at', $transaction->transacted_at?->format('Y-m-d\TH:i')) }}" />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-primary text-sm mb-1">> {{ __('DESCRIPTION') }}</label>
                    <x-forms.textarea id="description" name="description" rows="3"
                        placeholder="{{ __('Notes about this transaction...') }}" :value="old('description', $transaction->description)" />
                </div>
            </div>

            <div class="border-t border-border-color pt-6 flex justify-end gap-3">
                <x-button variant="outline"
                    href="{{ route('finance.transactions.index', array_filter(['agent' => $viewingOther ? $ownerId : null])) }}">
                    {{ __('CANCEL') }}
                </x-button>
                <x-button type="submit">[ {{ __('UPDATE TRANSACTION') }} ]</x-button>
            </div>
        </form>
    </div>
</x-app-layout>
