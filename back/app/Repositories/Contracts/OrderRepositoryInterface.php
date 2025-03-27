<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function create(array $orderData): object;

    public function createOrderItems(object $order, array $items): void;
}
