<?php

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\Checkout\Steps\OrderCreationStep;

describe('Order Creation Step', function () {
    it('creates order successfully', function () {
        $mockOrderRepository = Mockery::mock(OrderRepositoryInterface::class);
        $mockCartRepository = Mockery::mock(CartRepositoryInterface::class);

        $cart = new stdClass;
        $cart->user_id = 1;
        $cart->guest_id = null;

        $items = [
            ['product_id' => 1, 'quantity' => 2, 'price' => 10.00],
            ['product_id' => 2, 'quantity' => 1, 'price' => 20.00],
        ];

        $mockCartRepository
            ->shouldReceive('getTotalAmount')
            ->once()
            ->with($cart)
            ->andReturn(40.00);

        $mockCartRepository
            ->shouldReceive('getCartItems')
            ->once()
            ->with($cart)
            ->andReturn($items);

        $mockCartRepository->shouldReceive('clearCart')
            ->once()
            ->with($cart);

        $createdOrder = new stdClass;
        $createdOrder->id = 123;

        $mockOrderRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($createdOrder);

        $mockOrderRepository
            ->shouldReceive('createOrderItems')
            ->once();

        $orderCreationStep = new OrderCreationStep(
            $mockOrderRepository,
            $mockCartRepository
        );

        $result = $orderCreationStep->execute($cart);

        expect($result)->toBe($createdOrder);
    });
});
