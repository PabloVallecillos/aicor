<?php

namespace App\Http\Requests\Product;

use App\Traits\Requests\ListRequest;

class ProductListRequest extends ProductRequest
{
    use ListRequest;
}
