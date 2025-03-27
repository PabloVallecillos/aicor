<?php

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Checkout\Steps\InventoryUpdateStep;

describe('Inventory Update Step Test', function () {

    beforeEach(function () {
        $this->mockCartRepository = Mockery::mock(CartRepositoryInterface::class);
        $this->mockProductRepository = Mockery::mock(ProductRepositoryInterface::class);
    });

    it('reduces stock for cart items and clears the cart', function () {
        // Arrange
        $cart = new stdClass;
        $cart->user_id = 1;
        $cart->guest_id = null;

        $items = [
            ['product_id' => 1, 'quantity' => 2, 'price' => 10.00],
            ['product_id' => 2, 'quantity' => 1, 'price' => 20.00],
        ];

        $this->mockCartRepository
            ->shouldReceive('getCartItems')
            ->once()
            ->with($cart)
            ->andReturn($items);

        // Expect product stock reduction for each cart item
        $product1 = new stdClass;
        $product1->id = 1;
        $this->mockProductRepository
            ->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($product1);

        $this->mockProductRepository
            ->shouldReceive('reduceStock')
            ->once()
            ->with($product1, 2);

        $product2 = new stdClass;
        $product2->id = 2;
        $this->mockProductRepository
            ->shouldReceive('findById')
            ->once()
            ->with(2)
            ->andReturn($product2);

        $this->mockProductRepository
            ->shouldReceive('reduceStock')
            ->once()
            ->with($product2, 1);

        // Create the service with mocked product repository
        $inventoryUpdateStep = new InventoryUpdateStep($this->mockProductRepository, $this->mockCartRepository);

        // Act
        $result = $inventoryUpdateStep->execute($cart);

        // Assert
        expect($result)->toBeNull();
    });
});
