<?php

namespace App\Services\Checkout\Steps;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class InventoryUpdateStep extends BaseCheckoutStep
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly CartRepositoryInterface $cartRepository,
    ) {}

    public function execute(object $cart, ?object $previousResult = null): ?object
    {
        $items = $this->cartRepository->getCartItems($cart);

        foreach ($items as $item) {
            $product = $this->productRepository->findById($item['product_id']);
            $this->productRepository->reduceStock($product, $item['quantity']);
        }

        return null;
    }
}
