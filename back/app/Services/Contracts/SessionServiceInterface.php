<?php

namespace App\Services\Contracts;

interface SessionServiceInterface
{
    public function getSessionToken(): ?string;
}
