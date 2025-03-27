<?php

namespace App\Services\Checkout\Validators;

use App\Exceptions\PurchaseException;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\Checkout\Validators\Contracts\CartValidatorInterface;

readonly class CartValidator implements CartValidatorInterface
{
    public function __construct(private CartRepositoryInterface $cartRepository) {}

    /**
     * @throws PurchaseException
     */
    public function validate(object $cart): void
    {
        $items = $this->cartRepository->getCartItems($cart);
        if (empty($items)) {
            throw PurchaseException::invalidCart();
        }
    }
}
