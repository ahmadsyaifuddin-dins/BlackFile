<x-app-layout>
    <x-slot:title>
        {{ __('FINANCE // EDIT RECEIVABLE') }}
    </x-slot:title>

    @php
        $sourceOptions = $sources->pluck('name', 'id')->all();
    @endphp

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg font-mono">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="text-lg sm:text-2xl font-bold text-primary">
                > [ {{ __('EDIT RECEIVABLE') }}: {{ $receivable->debtor_name }} ]
            </h2>
            <x-button variant="outline"
                href="{{ route('finance.receivables.index', array_filter(['agent' => $viewingOther ? $ownerId : null])) }}"
                class="justify-center">
                &lt; {{ __('Back') }}
            </x-button>
        </div>

        @if (isset($errors) && $errors->any())
            <div class="mb-6 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg text-sm" role="alert">
                <p class="font-bold mb-2">> {{ __('Data Input Anomaly Detected') }}:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('finance.receivables.update', $receivable) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="debtor_name" class="block text-primary text-sm mb-1">> {{ __('DEBTOR NAME') }}</label>
                    <x-forms.input type="text" id="debtor_name" name="debtor_name"
                        value="{{ old('debtor_name', $receivable->debtor_name) }}" />
                </div>

                <div>
                    <label for="amount" class="block text-primary text-sm mb-1">> {{ __('AMOUNT (IDR)') }}</label>
                    <x-forms.money id="amount" name="amount" :value="old('amount', (float) $receivable->amount)"
                        placeholder="0" />
                </div>

                <div>
                    <label for="transacted_at" class="block text-primary text-sm mb-1">> {{ __('DATE & TIME') }}</label>
                    <x-forms.input type="datetime-local" id="transacted_at" name="transacted_at"
                        value="{{ old('transacted_at', $receivable->transacted_at?->format('Y-m-d\TH:i')) }}" />
                </div>

                <x-forms.select label="> {{ __('FUND SOURCE (OPTIONAL)') }}" name="fund_source_id"
                    :options="$sourceOptions" :selected="old('fund_source_id', $receivable->fund_source_id)"
                    placeholder="{{ __('-- No Fund Source --') }}" :searchable="true" />

                <div class="md:col-span-2">
                    <label for="description" class="block text-primary text-sm mb-1">> {{ __('DESCRIPTION') }}</label>
                    <x-forms.textarea id="description" name="description" rows="3" :value="old('description', $receivable->description)" />
                </div>

                <div class="md:col-span-2 p-3 bg-black/30 border border-border-color rounded">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_legacy" value="1"
                            class="form-checkbox-themed mt-1"
                            style="width:1.4rem;height:1.4rem;min-width:1.4rem;min-height:1.4rem;flex-shrink:0;align-self:flex-start;"
                            @checked(old('is_legacy', $receivable->is_legacy))>
                        <span class="text-sm text-[var(--color-text-default)] select-none">
                            {{ __('This is a PAST receivable (money already lent before this system — does NOT reduce your current balance)') }}
                        </span>
                    </label>
                </div>
            </div>

            <div class="border-t border-border-color pt-6 flex justify-end gap-3">
                <x-button variant="outline"
                    href="{{ route('finance.receivables.index', array_filter(['agent' => $viewingOther ? $ownerId : null])) }}">
                    {{ __('CANCEL') }}
                </x-button>
                <x-button type="submit">[ {{ __('UPDATE RECEIVABLE') }} ]</x-button>
            </div>
        </form>
    </div>
</x-app-layout>
