<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cash_category_id', 'type', 'amount', 'description',
        'vendor', 'transaction_date', 'is_recurring', 'recurring_interval',
        'source', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CashCategory::class, 'cash_category_id');
    }

    public function expenseScore(): HasOne
    {
        return $this->hasOne(ExpenseScore::class);
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeForPeriod($query, string $period)
    {
        $start = \Carbon\Carbon::parse($period . '-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();
        return $query->whereBetween('transaction_date', [$start, $end]);
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }
}
