<?php

namespace App\Services;

use App\Models\Cart;
use App\Services\Contracts\CartSessionServiceInterface;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;

readonly class CartSessionService implements CartSessionServiceInterface
{
    public function __construct(
        private Request $request,
        private CookieJar $cookieJar
    ) {}

    public function ensureSessionExists(): void
    {
        $sessionKey = SessionService::SESSION_KEY;

        if (! $this->request->cookie($sessionKey)) {
            $this->cookieJar->queue($sessionKey, Cart::generateGuestSession(), 60 * 24 * 30);
        }
    }

    public function getSessionToken(): ?string
    {
        return $this->request->cookie(SessionService::SESSION_KEY);
    }
}
