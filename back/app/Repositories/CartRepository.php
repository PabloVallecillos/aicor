<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use App\Services\Contracts\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function findByUserOrSession(?int $userId, ?string $sessionToken): ?object
    {
        return Cart::where(function ($query) use ($userId, $sessionToken) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('guest_id', $sessionToken);
            }
        })->first();
    }

    public function create(array $data): object
    {
        return Cart::create($data);
    }

    public function addItem(object $cart, Product $product, int $quantity): object
    {
        $cartItem = $cart->cartItems()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
                'price' => $product->price,
            ]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        $cart->refresh();

        return $cart;
    }

    public function removeItem(object $cart, Product $product): void
    {
        $cart->cartItems()
            ->where('product_id', $product->id)
            ->delete();
    }

    public function updateItemQuantity(object $cart, Product $product, int $quantity): object
    {
        $cartItem = $cart->cartItems()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            if ($quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update([
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }
        }

        $cart->refresh();

        return $cart;
    }

    public function clearCart(object $cart): void
    {
        $cart->cartItems()->delete();
    }
}
