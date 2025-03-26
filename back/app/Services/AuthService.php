<?php

namespace App\Services;

use App\Services\Contracts\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    public function getCurrentUser(): ?object
    {
        return auth()->user();
    }
}
