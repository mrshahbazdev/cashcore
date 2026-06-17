<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'cash_transaction_id', 'purpose', 'benefit',
        'revenue_score', 'efficiency_score', 'strategic_score',
        'usage_score', 'total_score', 'recommendation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CashTransaction::class, 'cash_transaction_id');
    }

    public function calculateTotal(): int
    {
        $this->total_score = $this->revenue_score + $this->efficiency_score
            + $this->strategic_score + $this->usage_score;

        if ($this->total_score >= 28) {
            $this->recommendation = 'keep';
        } elseif ($this->total_score >= 15) {
            $this->recommendation = 'reduce';
        } else {
            $this->recommendation = 'eliminate';
        }

        return $this->total_score;
    }
}
