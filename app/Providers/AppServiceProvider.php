<?php

namespace App\Providers;

use App\Models\Firm;
use App\Models\User;
use App\Services\CartService;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Inertia\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CartService::class);
        $this->app->bind(OrderService::class);
        $this->app->bind(NewsService::class);
        $this->app->bind(DetailService::class);
        $this->app->bind(UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::composer('Catalog/Index', function (ResponseFactory $inertia) {
            $inertia->with([
                'categories' => [
                    'brands' => Firm::all(),
                ],
            ]);
        });
    }
}
