<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderListRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends ResourceController
{
    public function __invoke(OrderListRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $filters['filters']['user_id'] = auth()->id();

        return response()->json(self::list(Order::class, $filters));
    }
}
