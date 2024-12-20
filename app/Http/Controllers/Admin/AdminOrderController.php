<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AdminOrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {

    }

    public function index(Request $request):Response
    {
        Log::info(strval($request->input('statuses')));

        return Inertia::render('Admin/Orders/OrderList', ['orders' => $this->orderService->getByStatus($request->input('statuses'))]);
    }

    public function show(int $id):Response
    {
        Log::info($id);
        return Inertia::render('Admin/Orders/OrderCard', ['order' => $this->orderService->getById($id), 'details' => $this->orderService->getOrderItems($id)]);
    }
}
