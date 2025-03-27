<?php

namespace App\Providers;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Contracts\PurchaseServiceInterface;
use App\Services\PurchaseService;
use App\Support\Contracts\DatabaseTransactionInterface;
use App\Support\DbFacadeTransaction;
use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(PurchaseServiceInterface::class, PurchaseService::class);
        $this->app->bind(DatabaseTransactionInterface::class, DbFacadeTransaction::class);

        $this->app->bind(PurchaseService::class, function ($app) {
            return new PurchaseService(
                $app->make(OrderRepositoryInterface::class),
                $app->make(ProductRepositoryInterface::class),
                $app->make(CartRepositoryInterface::class),
                $app->make(DbFacadeTransaction::class)
            );
        });
    }

    public function boot()
    {
        // Any additional boot logic
    }
}
