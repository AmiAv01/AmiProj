<?php

namespace App\Providers;

use App\Repositories\DetailRepository;
use App\Repositories\Interfaces\DetailRepositoryInterface;
use App\Services\Detail\DetailService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DetailRepositoryInterface::class, DetailRepository::class);
        $this->app->bind(DetailService::class, function ($app) {
            return new DetailService($app->make(DetailRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
