<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'alert_type', 'severity', 'title',
        'message', 'is_read', 'is_dismissed', 'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_dismissed' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false)->where('is_dismissed', false);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }
}
