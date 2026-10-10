<?php

namespace App\Http\Controllers\Finance;

use App\Models\FundSource;
use App\Models\FundSourceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FundSourceController extends FinanceController
{
    /**
     * Daftar sumber dana beserta saldo berjalannya.
     */
    public function index(Request $request)
    {
        $data = $this->financeViewData($request);

        $sources = FundSource::with('fundSourceType')
            ->where('user_id', $data['ownerId'])
            ->withSum(['transactions as income_sum' => fn ($q) => $q->where('type', 'in')], 'amount')
            ->withSum(['transactions as expense_sum' => fn ($q) => $q->where('type', 'out')], 'amount')
            ->orderBy('name')
            ->get();

        $totalBalance = $sources->sum(fn ($source) => $source->current_balance);

        return view('finance.fund_sources.index', $data + [
            'sources' => $sources,
            'totalBalance' => $totalBalance,
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);

        return view('finance.fund_sources.create', $data + [
            'types' => $this->typeOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);

        $validated = $this->validateSource($request, $data['ownerId']);

        FundSource::create([
            'user_id' => $data['ownerId'],
            'fund_source_type_id' => $validated['fund_source_type_id'],
            'name' => $validated['name'],
            'initial_balance' => $validated['initial_balance'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('finance.fund-sources.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Fund source has been registered.'));
    }

    public function edit(Request $request, FundSource $fundSource)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($fundSource, $data);

        return view('finance.fund_sources.edit', $data + [
            'source' => $fundSource,
            'types' => $this->typeOptions(),
        ]);
    }

    public function update(Request $request, FundSource $fundSource)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($fundSource, $data);

        $validated = $this->validateSource($request, $data['ownerId'], $fundSource->id);

        $fundSource->update([
            'fund_source_type_id' => $validated['fund_source_type_id'],
            'name' => $validated['name'],
            'initial_balance' => $validated['initial_balance'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('finance.fund-sources.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Fund source has been updated.'));
    }

    public function destroy(Request $request, FundSource $fundSource)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($fundSource, $data);

        if ($fundSource->transactions()->exists() || $fundSource->receivables()->exists()) {
            return redirect()
                ->route('finance.fund-sources.index')
                ->with('error', __('Cannot delete a fund source that still has transactions or receivables.'));
        }

        $fundSource->delete();

        return redirect()
            ->route('finance.fund-sources.index')
            ->with('success', __('Fund source has been deleted.'));
    }

    /**
     * Opsi dropdown dari data master (aktif saja).
     *
     * @return array<int, string>
     */
    private function typeOptions(): array
    {
        return FundSourceType::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * Aturan validasi yang dipakai store & update.
     *
     * Nilai '__other__' berarti user memilih opsi "Lainnya" dan mengetik
     * nama sumber dana sendiri (fund_source_type_id disimpan null).
     */
    private function validateSource(Request $request, int $ownerId, ?int $ignoreId = null): array
    {
        $isCustom = $request->input('fund_source_type_id') === self::CUSTOM_SOURCE;

        if ($isCustom) {
            $validated = $request->validate([
                'fund_source_type_id' => ['required', Rule::in([self::CUSTOM_SOURCE])],
                'custom_name' => [
                    'required', 'string', 'max:100',
                    Rule::unique('fund_sources', 'name')->where('user_id', $ownerId)->ignore($ignoreId),
                ],
                'initial_balance' => 'nullable|numeric|min:0',
                'description' => 'nullable|string|max:255',
            ], [
                'custom_name.required' => __('Custom name is required.'),
                'custom_name.max' => __('Custom name is too long.'),
                'custom_name.unique' => __('You already have a fund source with this name.'),
            ]);

            $validated['fund_source_type_id'] = null;
            $validated['name'] = trim($validated['custom_name']);

            return $validated;
        }

        $uniqueType = Rule::unique('fund_sources', 'fund_source_type_id')
            ->where('user_id', $ownerId);

        if ($ignoreId) {
            $uniqueType->ignore($ignoreId);
        }

        $validated = $request->validate([
            'fund_source_type_id' => [
                'required',
                Rule::exists('fund_source_types', 'id')->where('is_active', true),
                $uniqueType,
            ],
            'custom_name' => 'nullable',
            'initial_balance' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ], [
            'fund_source_type_id.required' => __('Please select a fund source.'),
            'fund_source_type_id.exists' => __('The selected fund source is not available.'),
            'fund_source_type_id.unique' => __('You already have a fund source of this type.'),
        ]);

        $validated['name'] = FundSourceType::find($validated['fund_source_type_id'])->name;

        return $validated;
    }

    /**
     * Nilai khusus untuk pilihan "Other" pada dropdown sumber dana.
     */
    public const CUSTOM_SOURCE = '__other__';
}
