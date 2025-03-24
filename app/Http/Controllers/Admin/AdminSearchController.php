<?php

namespace App\Http\Controllers\Admin;

use App\DTO\SearchQueryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSearchRequest;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Http\Requests\SearchFormRequest;
use App\Services\AdminSearchService\AdminDetailsSearchService;
use App\Services\AdminSearchService\AdminNewsSearchService;
use App\Services\AdminSearchService\AdminOrderSearchService;
use App\Services\AdminSearchService\AdminSearchInterface;
use App\Services\AdminSearchService\AdminUserSearchService;
use App\Services\AdminService;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSearchController extends Controller
{

    public function index(AdminSearchRequest $request)
    {
        $search = $request->validated('searchQ');
        $category = $request->validated('category');
        $class = match($category){
            'detail' => new AdminDetailsSearchService(app(DetailService::class)),
            'order' => new AdminOrderSearchService(app(OrderService::class)),
            'news' => new AdminNewsSearchService(app(NewsService::class)),
            'user' => new AdminUserSearchService(app(UserService::class)),
            default => throw new \Exception('Not found category')
        };

        return ["$category" => $class->search($search, 1000, 12)];
    }
}
