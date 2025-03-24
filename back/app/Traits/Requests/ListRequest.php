<?php

namespace App\Traits\Requests;

trait ListRequest
{
    public function rules(): array
    {
        return [
            'only_fields' => 'array',
            'hide_fields' => 'array',
            'filters' => 'array',
            'group' => 'array',
            'order' => 'array',
            'per_page' => 'bail|integer|min:1',
            'link_range' => 'bail|integer|min:1',
            'page' => 'bail|integer|min:1',
            'paginator_mode' => 'bail|integer|min:0|max:2',
        ];
    }
}
