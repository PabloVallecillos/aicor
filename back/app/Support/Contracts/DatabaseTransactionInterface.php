<?php

namespace App\Support\Contracts;

interface DatabaseTransactionInterface
{
    public function transaction(callable $callback);
}
