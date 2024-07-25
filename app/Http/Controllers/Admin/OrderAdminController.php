<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderAdminController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {

    }

    public function index(Request $request)
    {
        Log::info(strval($request->input('statuses')));

        return Inertia::render('Admin/Orders/OrderList', ['orders' => $this->orderService->getByStatus($request->input('statuses'))]);
    }
}
