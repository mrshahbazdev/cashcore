<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'type', 'icon', 'color', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class);
    }

    public static function getDefaults(int $userId): void
    {
        $defaults = [
            ['name' => 'Revenue / Sales', 'type' => 'income', 'icon' => '💰', 'color' => '#22c55e'],
            ['name' => 'Services', 'type' => 'income', 'icon' => '🛠️', 'color' => '#3b82f6'],
            ['name' => 'Other Income', 'type' => 'income', 'icon' => '📥', 'color' => '#8b5cf6'],
            ['name' => 'Rent / Office', 'type' => 'expense', 'icon' => '🏢', 'color' => '#ef4444'],
            ['name' => 'Software / Tools', 'type' => 'expense', 'icon' => '💻', 'color' => '#f59e0b'],
            ['name' => 'Salaries', 'type' => 'expense', 'icon' => '👥', 'color' => '#ec4899'],
            ['name' => 'Marketing', 'type' => 'expense', 'icon' => '📢', 'color' => '#14b8a6'],
            ['name' => 'Insurance', 'type' => 'expense', 'icon' => '🛡️', 'color' => '#6366f1'],
            ['name' => 'Travel', 'type' => 'expense', 'icon' => '✈️', 'color' => '#f97316'],
            ['name' => 'Miscellaneous', 'type' => 'expense', 'icon' => '📦', 'color' => '#78716c'],
        ];

        foreach ($defaults as $cat) {
            self::firstOrCreate(
                ['user_id' => $userId, 'name' => $cat['name']],
                array_merge($cat, ['user_id' => $userId, 'is_default' => true])
            );
        }
    }
}
