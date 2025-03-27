<?php

namespace App\Support;

use App\Support\Contracts\DatabaseTransactionInterface;
use Illuminate\Support\Facades\DB;

class DbFacadeTransaction implements DatabaseTransactionInterface
{
    public function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }
}
