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

    protected $appends = [
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function getImageAttribute(): ?string
    {
        $public = storage_path('app/public');
        $productDirectory = "$public/products/$this->id";

        if (! is_dir($productDirectory)) {
            return null;
        }

        $files = glob($productDirectory.'/*.{png,jpg,jpeg,webp}', GLOB_BRACE);

        if (empty($files)) {
            return null;
        }

        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $latestFile = $files[0];

        return str_replace($public, asset('storage'), $latestFile);
    }

    public function resourceDefaultFieldsFilter(): array
    {
        return ['name'];
    }

    public function resourceDefaultOrder(): array
    {
        return ['price' => 'ASC'];
    }
}
