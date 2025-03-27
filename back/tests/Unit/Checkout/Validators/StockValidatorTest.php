<?php

use App\Exceptions\PurchaseException;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Checkout\Validators\StockValidator;

describe('Stock Validator', function () {
    beforeEach(function () {
        $this->mockProductRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->mockCartRepository = Mockery::mock(CartRepositoryInterface::class);
    });

    it('validates product stock availability', function () {
        $cart = new stdClass;
        $items = [['product_id' => 1, 'quantity' => 5]];

        $this->mockCartRepository
            ->shouldReceive('getCartItems')
            ->once()
            ->andReturn($items);

        $product = (object) ['id' => 1];

        $this->mockProductRepository
            ->shouldReceive('findManyByIds')
            ->once()
            ->andReturn([1 => $product]);

        $this->mockProductRepository
            ->shouldReceive('checkAvailableStock')
            ->once()
            ->with($product, 5)
            ->andReturn(true);

        $stockValidator = new StockValidator($this->mockProductRepository, $this->mockCartRepository);

        expect(fn () => $stockValidator->validate($cart))
            ->not()->toThrow(PurchaseException::class);
    });

    it('throws exception on insufficient stock', function () {
        $cart = new stdClass;
        $items = [['product_id' => 1, 'quantity' => 999]];

        $this->mockCartRepository
            ->shouldReceive('getCartItems')
            ->once()
            ->andReturn($items);

        $product = (object) ['id' => 1];

        $this->mockProductRepository
            ->shouldReceive('findManyByIds')
            ->once()
            ->andReturn([1 => $product]);

        $this->mockProductRepository
            ->shouldReceive('checkAvailableStock')
            ->once()
            ->with($product, 999)
            ->andReturn(false);

        $stockValidator = new StockValidator($this->mockProductRepository, $this->mockCartRepository);

        expect(fn () => $stockValidator->validate($cart))
            ->toThrow(PurchaseException::class);
    });

    afterEach(function () {
        Mockery::close();
    });
});
