<?php

namespace App\Models;

use App\Traits\AdvancedResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use AdvancedResource, HasFactory;

    const STATUS_PENDING = 'pending';

    const STATUSES = [
        self::STATUS_PENDING,
        'processing',
        'completed',
        'cancelled',
    ];

    protected $fillable = [
        'user_id',
        'guest_id',
        'total_amount',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function resourceDefaultFieldsFilter(): array
    {
        return ['name'];
    }

    public function resourceDefaultOrder(): array
    {
        return ['created_at' => 'ASC'];
    }
}
