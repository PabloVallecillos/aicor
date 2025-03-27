<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\SessionServiceInterface;

readonly class CartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private AuthServiceInterface $authService,
        private SessionServiceInterface $sessionService
    ) {}

    public function getCart(): array
    {
        $cart = $this->getCurrentCart();

        return $this->getCartDetails($cart);
    }

    private function getCurrentCart(): object
    {
        $user = $this->authService->getCurrentUser();
        $sessionToken = $this->sessionService->getSessionToken();

        $cart = $this->cartRepository->findByUserOrSession(
            $user ? $user->id : null,
            $sessionToken
        );

        if (! $cart) {
            $cart = $this->cartRepository->create([
                'user_id' => $user ? $user->id : null,
                'guest_id' => $user ? null : $sessionToken,
                'session_token' => $sessionToken,
            ]);
        }

        return $cart;
    }

    public function addMultipleItems(array $products): array
    {
        $cart = $this->getCurrentCart();
        $updatedCart = $this->cartRepository->addMultipleItems($cart, $products);

        return $this->getCartDetails($updatedCart);
    }

    public function addItem(Product $product, int $quantity = 1): array
    {
        $cart = $this->getCurrentCart();
        $updatedCart = $this->cartRepository->addItem($cart, $product, $quantity);

        return $this->getCartDetails($updatedCart);
    }

    public function removeItem(Product $product): array
    {
        $cart = $this->getCurrentCart();
        $this->cartRepository->removeItem($cart, $product);

        return $this->getCartDetails($cart);
    }

    public function updateItemQuantity(Product $product, int $quantity): array
    {
        $cart = $this->getCurrentCart();
        $updatedCart = $this->cartRepository->updateItemQuantity($cart, $product, $quantity);

        return $this->getCartDetails($updatedCart);
    }

    public function clear(): array
    {
        $cart = $this->getCurrentCart();
        $this->cartRepository->clearCart($cart);

        return $this->getCartDetails($cart);
    }

    private function getCartDetails(object $cart): array
    {
        $items = $cart->cartItems ?? collect();

        return [
            'id' => $cart->id,
            'items' => $items->map(function ($item) {
                return [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                ];
            }),
            'total' => $items->sum(fn ($item) => $item->quantity * $item->price),
            'total_items' => $items->sum('quantity'),
        ];
    }
}
