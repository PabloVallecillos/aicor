<?php

namespace App\Services\Checkout\Steps;

use App\Services\Checkout\Steps\Contracts\StepInterface;

abstract class BaseCheckoutStep implements StepInterface
{
    abstract public function execute(object $cart, ?object $previousResult = null): ?object;
}
