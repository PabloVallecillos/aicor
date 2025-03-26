<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddMultipleItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|max:100',
            'items.*.product_id' => [
                'required',
                'integer',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'items.max' => __('You can add a maximum of :max items at once.'),
            'items.*.product_id.exists' => __('One or more products are invalid or not available.'),
            'items.*.quantity.max' => __('Maximum quantity per item is :max.'),
        ];
    }
}
