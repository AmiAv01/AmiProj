<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSearchController extends Controller
{

    public function index(Request $request, OrderService $orderService, DetailService $detailService, NewsService $newsService, UserService $userService)
    {
        $search = $request->input('searchQ');
        $category = $request->input('category');
        Log::info(strval($request));
        return match($category){
            'details' => ['details' => ($search) ? $detailService->getBySearching($search) : $detailService->getAll(12)],
            'orders' => ['orders' => ($search) ? $orderService->getBySearching($search) : $orderService->getAll(12)],
            'news' => ['news' => ($search) ? $newsService->getBySearching($search) : $newsService->getAll(12)],
            'users' => ['users' => ($search) ? $userService->getBySearching($search) : $userService->getAll(12)],
        };
    }
}
