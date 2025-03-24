<?php

namespace App\Models;

use App\Traits\AdvancedResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use AdvancedResource, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function resourceDefaultFieldsFilter(): array
    {
        return ['name'];
    }

    public function resourceDefaultOrder(): array
    {
        return ['price' => 'ASC'];
    }
}
