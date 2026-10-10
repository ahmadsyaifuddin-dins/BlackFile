<?php

namespace App\Http\Controllers\Finance;

use App\Models\CashTransaction;
use App\Models\FundSource;
use App\Models\Receivable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CashTransactionController extends FinanceController
{
    /**
     * Kategori siap pakai untuk Kas Masuk.
     *
     * @var array<int, string>
     */
    public const INCOME_CATEGORIES = [
        'Gaji', 'Bonus', 'Usaha', 'Investasi', 'Hadiah', 'Pemasukan Lain',
    ];

    /**
     * Kategori siap pakai untuk Kas Keluar.
     *
     * @var array<int, string>
     */
    public const EXPENSE_CATEGORIES = [
        'Makanan', 'Transportasi', 'Tagihan', 'Belanja', 'Kesehatan', 'Pendidikan',
        'Hiburan', 'Transfer', 'Bayar Utang', 'Pengeluaran Lain',
    ];

    /**
     * Tandai untuk opsi "kategori lain" pada dropdown (harus ditolak di server).
     */
    public const CUSTOM_CATEGORY = '__category_custom__';

    public function index(Request $request)
    {
        $data = $this->financeViewData($request);

        $query = CashTransaction::with('fundSource')
            ->where('user_id', $data['ownerId']);

        if ($request->filled('type') && in_array($request->input('type'), ['in', 'out'])) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('source')) {
            $query->where('fund_source_id', $request->input('source'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('description', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            });
        }

        $transactions = $query
            ->orderBy('transacted_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan saldo.
        $initialTotal = (float) FundSource::where('user_id', $data['ownerId'])->sum('initial_balance');
        $incomeTotal = (float) CashTransaction::where('user_id', $data['ownerId'])->where('type', 'in')->sum('amount');
        $expenseTotal = (float) CashTransaction::where('user_id', $data['ownerId'])->where('type', 'out')->sum('amount');

        $monthStart = now()->startOfMonth();
        $monthIncome = (float) CashTransaction::where('user_id', $data['ownerId'])
            ->where('type', 'in')->where('transacted_at', '>=', $monthStart)->sum('amount');
        $monthExpense = (float) CashTransaction::where('user_id', $data['ownerId'])
            ->where('type', 'out')->where('transacted_at', '>=', $monthStart)->sum('amount');

        $outstandingReceivable = (float) Receivable::where('user_id', $data['ownerId'])
            ->where('status', Receivable::STATUS_UNPAID)->sum('amount');

        $sources = FundSource::where('user_id', $data['ownerId'])->orderBy('name')->get();

        return view('finance.transactions.index', $data + [
            'transactions' => $transactions,
            'sources' => $sources,
            'totalBalance' => $initialTotal + $incomeTotal - $expenseTotal,
            'monthIncome' => $monthIncome,
            'monthExpense' => $monthExpense,
            'outstandingReceivable' => $outstandingReceivable,
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);

        $sources = FundSource::where('user_id', $data['ownerId'])->orderBy('name')->get();

        if ($sources->isEmpty()) {
            return redirect()->route('finance.fund-sources.create')
                ->with('warning', __('Create at least one fund source before recording a transaction.'));
        }

        return view('finance.transactions.create', $data + ['sources' => $sources] + $this->categoryViewData());
    }

    public function store(Request $request)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);

        $validated = $this->validateTransaction($request, $data['ownerId']);

        CashTransaction::create([
            'user_id' => $data['ownerId'],
            'fund_source_id' => $validated['fund_source_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'transacted_at' => $validated['transacted_at'],
        ]);

        $this->rememberCategory($data['ownerId'], $validated['type'], $validated['category'] ?? null);

        return redirect()
            ->route('finance.transactions.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Transaction has been recorded.'));
    }

    public function edit(Request $request, CashTransaction $transaction)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($transaction, $data);

        if ($transaction->receivable_id) {
            return redirect()->route('finance.transactions.index')
                ->with('error', __('This transaction was generated automatically by a receivable. Manage it from the Receivables page.'));
        }

        $sources = FundSource::where('user_id', $data['ownerId'])->orderBy('name')->get();

        return view('finance.transactions.edit', $data + [
            'transaction' => $transaction,
            'sources' => $sources,
        ] + $this->categoryViewData());
    }

    public function update(Request $request, CashTransaction $transaction)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($transaction, $data);

        if ($transaction->receivable_id) {
            return redirect()->route('finance.transactions.index')
                ->with('error', __('This transaction was generated automatically by a receivable. Manage it from the Receivables page.'));
        }

        $validated = $this->validateTransaction($request, $data['ownerId']);

        $transaction->update([
            'fund_source_id' => $validated['fund_source_id'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'transacted_at' => $validated['transacted_at'],
        ]);

        $this->rememberCategory($data['ownerId'], $validated['type'], $validated['category'] ?? null);

        return redirect()
            ->route('finance.transactions.index', array_filter(['agent' => $data['viewingOther'] ? $data['ownerId'] : null]))
            ->with('success', __('Transaction has been updated.'));
    }

    public function destroy(Request $request, CashTransaction $transaction)
    {
        $data = $this->financeViewData($request);
        $this->denyIfReadOnly($data);
        $this->ensureOwned($transaction, $data);

        if ($transaction->receivable_id) {
            return redirect()->route('finance.transactions.index')
                ->with('error', __('This transaction was generated automatically by a receivable. Manage it from the Receivables page.'));
        }

        $transaction->delete();

        return redirect()
            ->route('finance.transactions.index')
            ->with('success', __('Transaction has been deleted.'));
    }

    /**
     * Aturan validasi transaksi yang dipakai store & update.
     */
    private function validateTransaction(Request $request, int $ownerId): array
    {
        return $request->validate([
            'type' => ['required', Rule::in(['in', 'out'])],
            'amount' => 'required|numeric|min:0.01',
            'fund_source_id' => [
                'required',
                Rule::exists('fund_sources', 'id')->where('user_id', $ownerId),
            ],
            'category' => ['nullable', 'string', 'max:100', Rule::notIn([self::CUSTOM_CATEGORY])],
            'description' => 'nullable|string|max:1000',
            'transacted_at' => 'required|date',
        ], [
            'type.required' => __('Transaction type is required.'),
            'amount.required' => __('Amount is required.'),
            'amount.numeric' => __('Amount must be a valid number.'),
            'amount.min' => __('Amount must be greater than zero.'),
            'fund_source_id.required' => __('Please select a fund source.'),
            'fund_source_id.exists' => __('The selected fund source is not available.'),
            'category.max' => __('Category is too long.'),
            'category.not_in' => __('The selected category is not valid.'),
            'transacted_at.required' => __('Date & time is required.'),
            'transacted_at.date' => __('Date & time is invalid.'),
        ]);
    }

    /**
     * Data dropdown kategori (preset + custom milik user) dan default-nya.
     */
    private function categoryViewData(): array
    {
        $settings = $this->getOwnerSettings();

        $incomeCategories = $this->categoryOptions('in', $settings);
        $expenseCategories = $this->categoryOptions('out', $settings);

        return [
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'knownCategories' => array_values(array_unique(array_merge($incomeCategories, $expenseCategories))),
            'categoryCustom' => self::CUSTOM_CATEGORY,
            'defaultIncome' => $settings['finance_default_income_category'] ?? self::INCOME_CATEGORIES[0],
            'defaultExpense' => $settings['finance_default_expense_category'] ?? self::EXPENSE_CATEGORIES[3],
        ];
    }

    /**
     * Pengaturan pemilik data (self, karena create/edit hanya untuk akun sendiri).
     */
    private function getOwnerSettings(): array
    {
        return optional(Auth::user())->settings ?? [];
    }

    /**
     * Daftar kategori untuk satu jenis transaksi = preset + kategori custom user.
     */
    private function categoryOptions(string $type, array $settings): array
    {
        $presets = $type === 'in' ? self::INCOME_CATEGORIES : self::EXPENSE_CATEGORIES;
        $key = $type === 'in' ? 'finance_income_categories' : 'finance_expense_categories';
        $custom = $settings[$key] ?? [];
        $custom = is_array($custom) ? $custom : [];

        return array_values(array_unique(array_merge($presets, $custom)));
    }

    /**
     * Simpan kategori custom yang diketik user agar muncul di dropdown berikutnya.
     */
    private function rememberCategory(int $ownerId, string $type, ?string $category): void
    {
        if (! $category) {
            return;
        }

        $presets = $type === 'in' ? self::INCOME_CATEGORIES : self::EXPENSE_CATEGORIES;
        if (in_array($category, $presets, true)) {
            return;
        }

        $user = User::find($ownerId);

        if (! $user) {
            return;
        }

        $settings = $user->settings ?? [];
        $key = $type === 'in' ? 'finance_income_categories' : 'finance_expense_categories';
        $list = $settings[$key] ?? [];
        $list = is_array($list) ? $list : [];

        if (! in_array($category, $list, true)) {
            $list[] = $category;
            $settings[$key] = array_slice(array_values($list), -20);
            $user->settings = $settings;
            $user->save();
        }
    }
}
