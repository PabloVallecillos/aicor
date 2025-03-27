<?php

namespace App\Services\Checkout\Steps\Contracts;

interface StepInterface
{
    public function execute(object $cart): ?object;
}
