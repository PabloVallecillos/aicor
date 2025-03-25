<?php

namespace App\Services\Contracts;

interface CartSessionServiceInterface
{
    public function ensureSessionExists(): void;

    public function getSessionToken(): ?string;
}
