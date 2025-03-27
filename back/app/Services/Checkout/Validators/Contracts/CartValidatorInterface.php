<?php

namespace App\Services\Checkout\Validators\Contracts;

interface CartValidatorInterface
{
    public function validate(object $cart): void;
}
