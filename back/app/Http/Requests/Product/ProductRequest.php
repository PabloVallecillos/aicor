<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\AbstractRequest;

class ProductRequest extends AbstractRequest
{
    /**
     * Authorize user for request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Input param validation.
     */
    public function rules(): array
    {
        return [];
    }
}
