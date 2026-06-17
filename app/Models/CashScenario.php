<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashScenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'current_revenue',
        'current_costs', 'adjusted_revenue', 'adjusted_costs',
        'projected_profit', 'adjustments',
    ];

    protected $casts = [
        'current_revenue' => 'decimal:2',
        'current_costs' => 'decimal:2',
        'adjusted_revenue' => 'decimal:2',
        'adjusted_costs' => 'decimal:2',
        'projected_profit' => 'decimal:2',
        'adjustments' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateProjectedProfit(): float
    {
        $this->projected_profit = $this->adjusted_revenue - $this->adjusted_costs;
        return (float) $this->projected_profit;
    }

    public function getCurrentProfit(): float
    {
        return (float) ($this->current_revenue - $this->current_costs);
    }

    public function getProfitDelta(): float
    {
        return $this->calculateProjectedProfit() - $this->getCurrentProfit();
    }
}
