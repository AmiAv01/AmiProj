<?php

namespace App\Providers;

use App\Factories\AdminSearchServiceFactory;
use App\Factories\SearchServiceFactoryInterface;
use App\Services\Cart\CartItemService;
use app\Services\Cart\CartService;
use App\Services\CurrencyService;
use App\Services\DetailService;
use App\Services\Interface\ProductViewServiceInterface;
use App\Services\NewsService;
use App\Services\OemService;
use App\Services\OrderService;
use App\Services\PriceService;
use App\Services\Product\AnalogService;
use App\Services\Product\CompatiblePartsService;
use App\Services\Product\ProductImageService;
use App\Services\Product\ProductService;
use App\Services\Product\ProductViewService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CartService::class);
        $this->app->bind(CartItemService::class);
        $this->app->bind(OrderService::class);
        $this->app->bind(NewsService::class);
        $this->app->bind(DetailService::class);
        $this->app->bind(UserService::class);
        $this->app->bind(ProductService::class);
        $this->app->bind(PriceService::class);
        $this->app->bind(CurrencyService::class);
        $this->app->bind(
            ProductViewServiceInterface::class,
            ProductViewService::class
        );
        $this->app->bind(AnalogService::class);
        $this->app->bind(CompatiblePartsService::class);
        $this->app->bind(OemService::class);
        $this->app->bind(ProductImageService::class);
        $this->app->bind(SearchServiceFactoryInterface::class, AdminSearchServiceFactory::class, );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {}
}
