<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function addMultipleItems(object $cart, array $products): object
    {
        return DB::transaction(function () use ($cart, $products) {
            $productIds = array_map(fn ($item) => $item['product']->id, $products);

            $existingItems = $cart->cartItems()
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            $bulkUpdateData = [];
            $bulkInsertData = [];

            foreach ($products as $productData) {
                $product = $productData['product'];
                $quantity = $productData['quantity'];

                $existingItem = $existingItems->get($product->id);

                if ($existingItem) {
                    $bulkUpdateData[] = [
                        'id' => $existingItem->id,
                        'quantity' => $existingItem->quantity + $quantity,
                    ];
                } else {
                    $bulkInsertData[] = [
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (! empty($bulkUpdateData)) {
                $this->bulkUpdateCartItemQuantities($bulkUpdateData);
            }

            if (! empty($bulkInsertData)) {
                CartItem::insert($bulkInsertData);
            }

            $cart->refresh();

            return $cart;
        });
    }

    private function bulkUpdateCartItemQuantities(array $updateData): void
    {
        $cases = [];
        $ids = [];

        foreach ($updateData as $item) {
            $cases[] = "WHEN {$item['id']} THEN {$item['quantity']}";
            $ids[] = $item['id'];
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);

        DB::statement("
            UPDATE cart_items
            SET quantity = CASE id
                {$cases}
                END
            WHERE id IN ({$ids})
        ");
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
