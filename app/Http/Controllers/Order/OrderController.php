<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {

    }

    public function index()
    {
        $userId = auth()->check() ? auth()->user()->id : null;

        return Inertia::render('Order/OrderList', ['orders' => $this->orderService->getByUserId($userId)]);
    }

    public function store(Request $request)
    {

        $userId = auth()->check() ? auth()->user()->id : null;
        $order = $this->orderService->createOrder($request, $userId);

        return $order;
    }

    public function show(int $id)
    {
        return Inertia::render('Order/OrderCard', ['order' => $this->orderService->getById($id), 'details' => $this->orderService->getOrderItems($id)]);
    }

    public function update()
    {

    }
}
