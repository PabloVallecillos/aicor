<?php

namespace App\Exceptions;

use Exception;

class PurchaseException extends Exception
{
    public const INSUFFICIENT_STOCK = 'Insufficient stock';

    public const INVALID_CART = 'Invalid cart';

    public static function insufficientStock(int $id): self
    {
        return new self(
            sprintf('Insufficient stock for product id: %s', $id)
        );
    }

    public static function invalidCart(): self
    {
        return new self(self::INVALID_CART);
    }
}
