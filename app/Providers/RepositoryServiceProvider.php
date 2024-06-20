<?php

namespace App\Providers;

use App\Repositories\DetailRepository;
use App\Repositories\Interfaces\DetailRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            DetailRepositoryInterface::class,
            DetailRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
