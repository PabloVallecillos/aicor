<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $productId): object
    {
        return Product::findOrFail($productId);
    }

    public function checkAvailableStock(object $product, int $quantity): bool
    {
        return $product->stock >= $quantity;
    }

    public function reduceStock(object $product, int $quantity): void
    {
        $product->stock -= $quantity;
    }

    public function findManyByIds(array $productIds): array
    {
        return Product::select(['id', 'stock'])->whereIn('id', $productIds)->get()->keyBy('id')->toArray();
    }
}
