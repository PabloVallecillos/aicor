<?php

use App\Exceptions\PurchaseException;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\Checkout\Validators\CartValidator;

describe('Cart Validator', function () {
    beforeEach(function () {
        $this->mockCartRepository = Mockery::mock(CartRepositoryInterface::class);
    });

    it('detects empty cart', function () {
        $this->mockCartRepository
            ->shouldReceive('getCartItems')
            ->once()
            ->andReturn([]);

        $cart = new stdClass;
        $cartValidator = new CartValidator($this->mockCartRepository);

        expect(fn () => $cartValidator->validate($cart))
            ->toThrow(PurchaseException::class, 'Invalid cart');
    });

    it('passes with cart items', function () {
        $this->mockCartRepository
            ->shouldReceive('getCartItems')
            ->once()
            ->andReturn([['product_id' => 1, 'quantity' => 2]]);

        $cart = new stdClass;
        $cartValidator = new CartValidator($this->mockCartRepository);

        expect(fn () => $cartValidator->validate($cart))
            ->not()->toThrow(PurchaseException::class);
    });

    afterEach(function () {
        Mockery::close();
    });
});
