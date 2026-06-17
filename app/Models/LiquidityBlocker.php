<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidityBlocker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'blocker_type', 'title', 'description',
        'blocked_amount', 'due_date', 'debtor_name', 'days_overdue',
        'status', 'action_items',
    ];

    protected $casts = [
        'blocked_amount' => 'decimal:2',
        'due_date' => 'date',
        'action_items' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'in_progress']);
    }

    public static function getBlockerTypeLabel(string $type): string
    {
        return match ($type) {
            'open_invoice' => 'Open Invoice',
            'payment_terms' => 'Unfavorable Payment Terms',
            'inventory' => 'Excess Inventory',
            'inefficient_flow' => 'Inefficient Payment Flow',
            default => $type,
        };
    }
}
