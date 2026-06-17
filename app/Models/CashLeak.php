<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashLeak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cash_transaction_id', 'leak_type', 'title',
        'description', 'monthly_amount', 'leak_score', 'status',
        'recommendation',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CashTransaction::class, 'cash_transaction_id');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['detected', 'reviewed']);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public static function getLeakTypeLabel(string $type): string
    {
        return match ($type) {
            'rising_costs' => 'Rising Costs',
            'unused_subscription' => 'Unused Subscription',
            'duplicate_tool' => 'Duplicate Tool',
            'dead_expense' => 'Dead Expense',
            'no_function' => 'No Clear Function',
            default => $type,
        };
    }
}
