<?php

namespace App\Traits\Requests;

trait RequiredUpdateRequest
{
    /**
     * Array of fields not to upgrade to required.
     */
    abstract protected function ignoreRequired(): array;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        return $this->addRequired($rules);
    }

    public function addRequired($rules)
    {
        $ignored = $this->ignoreRequired();
        foreach ($rules as $field => $rule) {
            if (is_array($rule) && ! in_array($field, $ignored)) {
                array_unshift($rules[$field], 'required');
            }
        }

        return $rules;
    }
}
