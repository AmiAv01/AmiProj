<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSearchController extends Controller
{
    public function __construct(protected OrderService $orderService, protected DetailService $detailService, protected NewsService $newsService)
    {

    }

    public function index(Request $request)
    {
        $search = $request->input('searchQ');
        $category = $request->input('category');
        Log::info(strval($request));
        switch ($category) {
            case 'details':
                $details = ($search) ? $this->detailService->getBySearching($search) : $this->detailService->getAll();

                return ['details' => $details];
                break;
            case 'orders':
                $orders = ($search) ? $this->orderService->getBySearching($search) : $this->orderService->getAll();

                return ['orders' => $orders];
                break;
            case 'news':
                $news = ($search) ? $this->newsService->getBySearching($search) : $this->newsService->getAll();

                return ['news' => $news];

        }
    }
}
