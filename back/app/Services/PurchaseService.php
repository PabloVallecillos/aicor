<?php

namespace App\Services;

use App\Exceptions\PurchaseException;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Checkout\CheckoutProcess;
use App\Services\Checkout\Steps\InventoryUpdateStep;
use App\Services\Checkout\Steps\OrderCreationStep;
use App\Services\Checkout\Validators\CartValidator;
use App\Services\Checkout\Validators\StockValidator;
use App\Services\Contracts\PurchaseServiceInterface;
use App\Support\Contracts\DatabaseTransactionInterface;

readonly class PurchaseService implements PurchaseServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository,
        private CartRepositoryInterface $cartRepository,
        private DatabaseTransactionInterface $transaction
    ) {}

    /**
     * @throws PurchaseException
     */
    public function confirmPurchase(?int $userId = null, ?string $sessionToken = null): object
    {
        $cart = $this->cartRepository->findByUserOrSession($userId, $sessionToken);
        if (! $cart) {
            throw PurchaseException::invalidCart();
        }

        return $this->transaction->transaction(function () use ($cart) {
            $checkoutProcess = new CheckoutProcess($cart);

            $checkoutProcess
                ->addValidator(new CartValidator($this->cartRepository))
                ->addValidator(new StockValidator($this->productRepository, $this->cartRepository))
                ->addStep(new InventoryUpdateStep($this->productRepository, $this->cartRepository))
                ->addStep(new OrderCreationStep($this->orderRepository, $this->cartRepository));

            return $checkoutProcess->process();
        });
    }
}
