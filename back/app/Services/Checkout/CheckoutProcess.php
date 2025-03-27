<?php

namespace App\Services\Checkout;

use App\Services\Checkout\Steps\BaseCheckoutStep;
use App\Services\Checkout\Steps\Contracts\StepInterface;
use App\Services\Checkout\Validators\Contracts\CartValidatorInterface;

class CheckoutProcess
{
    /** @var BaseCheckoutStep[] */
    private array $steps = [];

    /** @var CartValidatorInterface[] */
    private array $validators = [];

    public function __construct(private readonly object $cart) {}

    public function addValidator(CartValidatorInterface $validator): self
    {
        $this->validators[] = $validator;

        return $this;
    }

    public function addStep(StepInterface $step): self
    {
        $this->steps[] = $step;

        return $this;
    }

    public function process(): ?object
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this->cart);
        }

        $result = null;
        foreach ($this->steps as $step) {
            $result = $step->execute($this->cart, $result);
        }

        return $result;
    }
}
