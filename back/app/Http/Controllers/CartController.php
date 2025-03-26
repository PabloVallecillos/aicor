<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\CartSessionService;
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
