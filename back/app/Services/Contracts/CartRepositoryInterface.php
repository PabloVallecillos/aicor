<?php

namespace App\Services\Contracts;

use App\Models\Product;

interface CartRepositoryInterface
{
    public function findByUserOrSession(?int $userId, ?string $sessionToken): ?object;

    public function create(array $data): object;

    public function addMultipleItems(object $cart, array $products): object;

    public function addItem(object $cart, Product $product, int $quantity): object;

    public function removeItem(object $cart, Product $product): void;

    public function updateItemQuantity(object $cart, Product $product, int $quantity): object;

    public function clearCart(object $cart): void;
}
