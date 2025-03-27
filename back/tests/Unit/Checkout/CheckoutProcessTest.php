<?php

use App\Services\Checkout\CheckoutProcess;
use App\Services\Checkout\Steps\Contracts\StepInterface;
use App\Services\Checkout\Validators\Contracts\CartValidatorInterface;

function createMockValidator(bool $validationResult = true): CartValidatorInterface
{
    $mockValidator = Mockery::mock(CartValidatorInterface::class);
    $mockValidator
        ->shouldReceive('validate')
        ->once()
        ->andReturn($validationResult);

    return $mockValidator;
}

function createMockStep(): StepInterface
{
    $mockStep = Mockery::mock(StepInterface::class);
    $mockStep
        ->shouldReceive('execute')
        ->once();

    return $mockStep;
}

describe('Checkout Process', function () {
    beforeEach(function () {
        $this->mockCart = new stdClass;
        $this->checkoutProcess = new CheckoutProcess($this->mockCart);
    });

    it('adds validator and returns self', function () {
        $mockValidator = Mockery::mock(CartValidatorInterface::class);
        $result = $this->checkoutProcess->addValidator($mockValidator);

        expect($result)->toBe($this->checkoutProcess);
    });

    it('adds step and returns self', function () {
        $mockStep = Mockery::mock(StepInterface::class);
        $result = $this->checkoutProcess->addStep($mockStep);

        expect($result)->toBe($this->checkoutProcess);
    });

    it('processes successfully with multiple validators and steps', function () {
        // Arrange
        $validator1 = createMockValidator();
        $validator2 = createMockValidator();
        $step1 = createMockStep();
        $step2 = createMockStep();

        // Act
        $this->checkoutProcess
            ->addValidator($validator1)
            ->addValidator($validator2)
            ->addStep($step1)
            ->addStep($step2)
            ->process();

        // Assert - handled by mock expectations
        expect(true)->toBeTrue();
    });
});
