<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Services\AuthService;
use App\Services\CartService;
use App\Services\CartSessionService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\CartSessionServiceInterface;
use App\Services\Contracts\SessionServiceInterface;
use App\Services\SessionService;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(SessionServiceInterface::class, SessionService::class);

        $this->app->bind(CartService::class, function ($app) {
            return new CartService(
                $app->make(CartRepositoryInterface::class),
                $app->make(AuthServiceInterface::class),
                $app->make(SessionServiceInterface::class)
            );
        });

        $this->app->bind(CartSessionServiceInterface::class, function ($app) {
            return new CartSessionService(
                $app->make(Request::class),
                $app->make(CookieJar::class)
            );
        });
    }

    public function boot()
    {
        // Any additional boot logic
    }
}
