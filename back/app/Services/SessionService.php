<?php

namespace App\Services;

use App\Services\Contracts\SessionServiceInterface;
use Illuminate\Http\Request;

readonly class SessionService implements SessionServiceInterface
{
    public const SESSION_KEY = 'cart_session';

    public function __construct(private Request $request) {}

    public function getSessionToken(): ?string
    {
        return $this->request->cookie(self::SESSION_KEY);
    }
}
