<?php

namespace App\Services\Checkout\Steps;

use App\Models\Order;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderCreationStep extends BaseCheckoutStep
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CartRepositoryInterface $cartRepository
    ) {}

    public function execute(object $cart, ?object $previousResult = null): object
    {
        $total = $this->cartRepository->getTotalAmount($cart);
        $order = $this->orderRepository->create([
            'user_id' => $cart->user_id,
            'guest_id' => $cart->guest_id,
            'total_amount' => $total,
            'status' => Order::STATUS_PENDING,
        ]);

        $items = $this->cartRepository->getCartItems($cart);
        $orderItems = array_map(fn ($item) => [
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ], $items);

        $this->orderRepository->createOrderItems($order, $orderItems);
        $this->cartRepository->clearCart($cart);

        return $order;
    }
}
