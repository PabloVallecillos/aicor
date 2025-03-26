<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_id',
        'session_token',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeOldAnonymous(Builder $query): Builder
    {
        return $query
            ->whereNull('user_id')
            ->where('created_at', '<', now()->subDays(30));
    }

    public static function generateGuestSession(): string
    {
        return Str::uuid();
    }
}
