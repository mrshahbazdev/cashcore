<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bucket', 'percentage', 'allocated_amount',
        'period', 'notes',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'allocated_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getDefaultBuckets(): array
    {
        return [
            ['bucket' => 'profit', 'percentage' => 10],
            ['bucket' => 'taxes', 'percentage' => 15],
            ['bucket' => 'salary', 'percentage' => 50],
            ['bucket' => 'operations', 'percentage' => 25],
        ];
    }

    public static function initializeForUser(int $userId, string $period): void
    {
        foreach (self::getDefaultBuckets() as $bucket) {
            self::firstOrCreate(
                ['user_id' => $userId, 'bucket' => $bucket['bucket'], 'period' => $period],
                array_merge($bucket, ['user_id' => $userId, 'period' => $period, 'allocated_amount' => 0])
            );
        }
    }
}
