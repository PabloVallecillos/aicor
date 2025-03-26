<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function getCurrentUser(): ?object;
}
