<?php

namespace App\Services\Contracts;

interface PurchaseServiceInterface
{
    public function confirmPurchase(?int $userId = null, ?string $sessionToken = null): object;
}
