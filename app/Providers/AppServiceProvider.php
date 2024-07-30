<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Product\ProductRepository;
use App\Repositories\EnumOption\EnumOptionRepository;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\ProductOutflow\ProductOutflowRepository;
use App\Repositories\EnumOption\EnumOptionRepositoryInterface;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\ProductOutflow\ProductOutflowRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductOutflowRepositoryInterface::class, ProductOutflowRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(EnumOptionRepositoryInterface::class, EnumOptionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
