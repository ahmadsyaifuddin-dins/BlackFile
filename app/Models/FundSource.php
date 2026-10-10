<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FundSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fund_source_type_id',
        'name',
        'initial_balance',
        'description',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fundSourceType(): BelongsTo
    {
        return $this->belongsTo(FundSourceType::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function receivables(): HasMany
    {
        return $this->hasMany(Receivable::class);
    }

    /**
     * Saldo berjalan = saldo awal + total kas masuk - total kas keluar.
     *
     * Kalau income_sum / expense_sum sudah di-eager load (withSum), pakai nilai
     * itu agar tidak terjadi N+1 query, jika tidak hitung langsung.
     */
    public function getCurrentBalanceAttribute(): float
    {
        $income = $this->income_sum !== null
            ? (float) $this->income_sum
            : (float) $this->transactions()->where('type', 'in')->sum('amount');

        $expense = $this->expense_sum !== null
            ? (float) $this->expense_sum
            : (float) $this->transactions()->where('type', 'out')->sum('amount');

        return (float) $this->initial_balance + $income - $expense;
    }
}
