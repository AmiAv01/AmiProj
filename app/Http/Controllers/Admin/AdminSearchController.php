<?php

namespace App\Http\Controllers\Admin;

use App\DTO\SearchQueryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSearchRequest;
use App\Http\Requests\SearchCatalogFormRequest;
use App\Http\Requests\SearchFormRequest;
use App\Services\AdminService;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSearchController extends Controller
{
    public function __construct(protected AdminService $adminService)
    {}

    public function index(AdminSearchRequest $request)
    {
        $search = $request->validated('searchQ');
        $category = $request->validated('category');
        $service = $this->adminService->chooseService($category);
        return ["$category" => ($search) ? $service->getBySearching($search) : $service->getAll(12)];
    }
}
