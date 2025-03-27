<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Services\Contracts\CartSessionServiceInterface;
use App\Services\PurchaseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ConfirmPurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseService $purchaseService,
        private readonly CartSessionServiceInterface $cartSessionService
    ) {}

    public function __invoke(): JsonResponse
    {
        try {
            $this->cartSessionService->ensureSessionExists();

            $userId = auth()->check() ? auth()->id() : null;
            $sessionToken = $this->cartSessionService->getSessionToken();

            $order = $this->purchaseService->confirmPurchase($userId, $sessionToken);

            return response()->json(
                new OrderResource($order),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }
    }
}
