<?php

namespace App\Http\Controllers\Finance;

use App\Models\CashTransaction;
use App\Models\FundSource;
use App\Models\Receivable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ReceivableController extends FinanceController
{
    public function index(Request $request)
    {
        $data = $this->financeViewData($request);

        $query = Receivable::with('fundSource')->where('user_id', $data['ownerId']);

        if ($request->filled('status') && in_array($request->input('status'), ['unpaid', 'paid'])) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('debtor_name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            });
        }

        $receivables = $query
            ->orderBy('transacted_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $unpaidTotal = (float) Receivable::where('user_id', $data['ownerId'])
            ->where('status', Receivable::STATUS_UNPAID)->sum('amount');
        $paidTotal = (float) Receivable::where('user_id', $data['ownerId'])
            ->where('status', Receivable::STATUS_PAID)->sum('amount');

        return view('finance.receivables.index', $data + [
            'receivables' => $receivables,
            'unpaidTotal' => $unpaidTotal,
            'paidTotal' => $paidTotal,
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);

        $sources = FundSource::where('user_id', $data['ownerId'])->orderBy('name')->get();

        return view('finance.receivables.create', $data + [
            'sources' => $sources,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);

        $validated = $this->validateReceivable($request, $data['ownerId']);

        $receivable = Receivable::create([
            'user_id' => $data['ownerId'],
            'fund_source_id' => $validated['fund_source_id'] ?? null,
            'debtor_name' => $validated['debtor_name'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'is_legacy' => $request->boolean('is_legacy'),
            'status' => Receivable::STATUS_UNPAID,
            'transacted_at' => $validated['transacted_at'],
        ]);

        $this->syncTransactions($receivable);

        return redirect()
            ->route('finance.receivables.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Receivable has been recorded.'));
    }

    public function edit(Request $request, Receivable $receivable)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($receivable, $data);

        $sources = FundSource::where('user_id', $data['ownerId'])->orderBy('name')->get();

        return view('finance.receivables.edit', $data + [
            'receivable' => $receivable,
            'sources' => $sources,
        ]);
    }

    public function update(Request $request, Receivable $receivable)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($receivable, $data);

        $validated = $this->validateReceivable($request, $data['ownerId']);

        $receivable->update([
            'fund_source_id' => $validated['fund_source_id'] ?? null,
            'debtor_name' => $validated['debtor_name'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'is_legacy' => $request->boolean('is_legacy'),
            'transacted_at' => $validated['transacted_at'],
        ]);

        $this->syncTransactions($receivable);

        return redirect()
            ->route('finance.receivables.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Receivable has been updated.'));
    }

    public function destroy(Request $request, Receivable $receivable)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($receivable, $data);

        $receivable->transactions()->delete();
        $receivable->delete();

        return redirect()
            ->route('finance.receivables.index')
            ->with('success', __('Receivable has been deleted.'));
    }

    /**
     * Tandai piutang sebagai lunas dan catat kas masuk.
     */
    public function settle(Request $request, Receivable $receivable)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($receivable, $data);

        if (! $receivable->isPaid()) {
            $receivable->update([
                'status' => Receivable::STATUS_PAID,
                'paid_at' => Carbon::now(),
            ]);

            $this->syncTransactions($receivable);
        }

        return redirect()
            ->route('finance.receivables.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Receivable marked as settled.'));
    }

    /**
     * Batalkan status lunas dan hapus kas masuk yang dihasilkan.
     */
    public function unsettle(Request $request, Receivable $receivable)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($receivable, $data);

        $receivable->update([
            'status' => Receivable::STATUS_UNPAID,
            'paid_at' => null,
        ]);

        $this->syncTransactions($receivable);

        return redirect()
            ->route('finance.receivables.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Receivable settlement has been reverted.'));
    }

    /**
     * Bangun ulang transaksi kas yang terhubung dengan piutang.
     *
     * Aturan:
     * - Piutang biasa (bukan legacy) dengan sumber dana -> kas keluar saat dibuat.
     * - Piutang legacy -> TIDAK mengurangi saldo saat diinput.
     * - Saat lunas -> kas masuk (legacy maupun bukan).
     */
    private function syncTransactions(Receivable $receivable): void
    {
        CashTransaction::where('receivable_id', $receivable->id)->delete();

        if (! $receivable->fund_source_id) {
            return;
        }

        if (! $receivable->is_legacy) {
            $this->createLinkedTransaction(
                $receivable,
                CashTransaction::TYPE_OUT,
                $receivable->transacted_at,
                __('Receivable issued: ').$receivable->debtor_name
            );
        }

        if ($receivable->isPaid()) {
            $this->createLinkedTransaction(
                $receivable,
                CashTransaction::TYPE_IN,
                $receivable->paid_at ?? Carbon::now(),
                __('Receivable settled: ').$receivable->debtor_name
            );
        }
    }

    private function createLinkedTransaction(Receivable $receivable, string $type, $when, string $description): void
    {
        CashTransaction::create([
            'user_id' => $receivable->user_id,
            'fund_source_id' => $receivable->fund_source_id,
            'receivable_id' => $receivable->id,
            'type' => $type,
            'amount' => $receivable->amount,
            'category' => 'Piutang',
            'description' => $description,
            'transacted_at' => $when,
        ]);
    }

    private function validateReceivable(Request $request, int $ownerId): array
    {
        return $request->validate([
            'debtor_name' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0.01',
            'fund_source_id' => [
                'nullable',
                Rule::exists('fund_sources', 'id')->where('user_id', $ownerId),
            ],
            'description' => 'nullable|string|max:1000',
            'transacted_at' => 'required|date',
        ], [
            'debtor_name.required' => __('Debtor name is required.'),
            'debtor_name.max' => __('Debtor name is too long.'),
            'amount.required' => __('Amount is required.'),
            'amount.numeric' => __('Amount must be a valid number.'),
            'amount.min' => __('Amount must be greater than zero.'),
            'fund_source_id.exists' => __('The selected fund source is not available.'),
            'transacted_at.required' => __('Date & time is required.'),
            'transacted_at.date' => __('Date & time is invalid.'),
        ]);
    }
}
