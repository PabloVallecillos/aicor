<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $orderData): object
    {
        return Order::create($orderData);
    }

    public function createOrderItems(object $order, array $items): void
    {
        $orderItems = [];
        foreach ($items as $item) {
            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
        }

        OrderItem::insert($orderItems);
    }
}
