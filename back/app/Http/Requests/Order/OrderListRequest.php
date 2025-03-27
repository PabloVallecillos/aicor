<?php

namespace App\Http\Requests\Order;

use App\Traits\Requests\ListRequest;

class OrderListRequest extends OrderRequest
{
    use ListRequest;
}
