<x-app-layout>
    <x-slot:title>
        {{ __('FINANCE // NEW FUND SOURCE') }}
    </x-slot:title>

    <div class="p-4 md:p-6 bg-surface border border-border-color rounded-lg font-mono">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h2 class="text-lg sm:text-2xl font-bold text-primary">
                > [ {{ __('REGISTER FUND SOURCE') }} ]
            </h2>
            <x-button variant="outline" href="{{ route('finance.fund-sources.index') }}" class="justify-center">
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

        <form method="POST" action="{{ route('finance.fund-sources.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div x-data="{ showCustom: {{ (string) old('fund_source_type_id') === \App\Http\Controllers\Finance\FundSourceController::CUSTOM_SOURCE ? 'true' : 'false' }} }"
                    @select-changed="showCustom = ($event.detail === '{{ \App\Http\Controllers\Finance\FundSourceController::CUSTOM_SOURCE }}')">
                    <label for="fund_source_type_id" class="block text-primary text-sm mb-1">> {{ __('FUND SOURCE') }}</label>
                    <x-forms.select name="fund_source_type_id"
                        :options="$types + ['__other__' => __('Other (type your own)')]"
                        :selected="old('fund_source_type_id')" searchable
                        placeholder="{{ __('-- Select Fund Source --') }}" />

                    <div x-show="showCustom" style="display: none;" class="mt-3">
                        <label for="custom_name" class="block text-primary text-sm mb-1">> {{ __('CUSTOM SOURCE NAME') }}</label>
                        <x-forms.input type="text" id="custom_name" name="custom_name" value="{{ old('custom_name') }}"
                            placeholder="{{ __('e.g. Tabungan Pulsa, Titipan Ibu...') }}" />
                    </div>
                </div>

                <div>
                    <label for="initial_balance" class="block text-primary text-sm mb-1">> {{ __('INITIAL BALANCE (IDR)') }}</label>
                    <x-forms.money id="initial_balance" name="initial_balance"
                        :value="old('initial_balance', 0)" placeholder="0" />
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-primary text-sm mb-1">> {{ __('DESCRIPTION (OPTIONAL)') }}</label>
                    <x-forms.textarea id="description" name="description" rows="2"
                        placeholder="{{ __('Short note about this fund source...') }}" :value="old('description')" />
                </div>
            </div>

            <div class="border-t border-border-color pt-6 flex justify-end">
                <x-button type="submit">[ {{ __('SAVE FUND SOURCE') }} ]</x-button>
            </div>
        </form>
    </div>
</x-app-layout>
