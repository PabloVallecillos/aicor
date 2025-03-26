<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddMultipleItemsRequest;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CartSessionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CartSessionService $cartSessionService
    ) {
        $this->cartSessionService->ensureSessionExists();
    }

    public function get(): JsonResponse
    {
        return response()->json($this->cartService->getCart());
    }

    public function addMultipleItems(AddMultipleItemsRequest $request): JsonResponse
    {
        $productIds = collect($request->validated('items'))
            ->pluck('product_id')
            ->unique()
            ->toArray();

        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $productsData = collect($request->validated('items'))
            ->map(function ($item) use ($products) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    throw new ModelNotFoundException(
                        __('Product with ID :id not found', ['id' => $item['product_id']])
                    );
                }

                return [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];
            })
            ->toArray();

        $cart = $this->cartService->addMultipleItems($productsData);

        return response()->json($cart);
    }

    public function addItem(Product $product): JsonResponse
    {
        return response()->json($this->cartService->addItem($product));
    }

    public function removeItem(Product $product): JsonResponse
    {
        return response()->json($this->cartService->removeItem($product));
    }

    public function updateQuantity(Product $product, int $quantity): JsonResponse
    {
        return response()->json($this->cartService->updateItemQuantity($product, $quantity));
    }

    public function clear(): JsonResponse
    {
        return response()->json($this->cartService->clear());
    }
}
