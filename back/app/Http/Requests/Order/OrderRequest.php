<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\AbstractRequest;

class OrderRequest extends AbstractRequest
{
    /**
     * Authorize user for request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Input param validation.
     */
    public function rules(): array
    {
        return [];
    }
}
