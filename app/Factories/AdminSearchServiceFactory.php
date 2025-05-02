<?php

namespace App\Factories;

use App\Enums\Category;
use App\Services\AdminSearchService\AdminDetailsSearchService;
use App\Services\AdminSearchService\AdminNewsSearchService;
use App\Services\AdminSearchService\AdminOrderSearchService;
use App\Services\AdminSearchService\AdminSearchInterface;
use App\Services\AdminSearchService\AdminUserSearchService;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use App\Services\UserService;

final class AdminSearchServiceFactory implements SearchServiceFactoryInterface
{
    public function create(string $category): AdminSearchInterface {
        $categoryEnum = Category::tryFrom($category);
        return match ($categoryEnum) {
            Category::DETAILS => new AdminDetailsSearchService(app(DetailService::class)),
            Category::ORDERS => new AdminOrderSearchService(app(OrderService::class)),
            Category::NEWS => new AdminNewsSearchService(app(NewsService::class)),
            Category::USERS => new AdminUserSearchService(app(UserService::class)),
            default => throw new \Exception('Not found category')
        };
    }
}
