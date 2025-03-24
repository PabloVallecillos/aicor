<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductListRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends ResourceController
{
    public function __invoke(ProductListRequest $request): JsonResponse
    {
        return response()->json(self::list(Product::class, $request->all()));
    }
}
