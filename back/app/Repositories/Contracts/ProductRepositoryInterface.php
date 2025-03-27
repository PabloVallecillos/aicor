<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function findById(int $productId): object;

    public function checkAvailableStock(object $product, int $quantity): bool;

    public function reduceStock(object $product, int $quantity): void;

    public function findManyByIds(array $productIds): array;
}
