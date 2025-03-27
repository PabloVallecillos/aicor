<?php

namespace App\Services\Checkout\Validators;

use App\Exceptions\PurchaseException;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Checkout\Validators\Contracts\CartValidatorInterface;

readonly class StockValidator implements CartValidatorInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function validate(object $cart): void
    {
        $cartRepository = $this->cartRepository;
        $items = $cartRepository->getCartItems($cart);
        $productIds = array_column($items, 'product_id');
        $products = $this->productRepository->findManyByIds($productIds);

        foreach ($items as $item) {
            $product = $products[$item['product_id']] ?? null;
            if (! $product || ! $this->productRepository->checkAvailableStock((object) $product, $item['quantity'])) {
                throw PurchaseException::insufficientStock($product->id);
            }
        }
    }
}
