<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {

    }

    public function index(): Response
    {
        $userId = auth()->check() ? auth()->user()->id : null;

        return Inertia::render('Order/OrderList', ['orders' => $this->orderService->getByUserId($userId)]);
    }

    public function store(Request $request): Order
    {
        $order = $this->orderService->createOrder(auth()->id(), $request['totalPrice']);
        return $order;
    }

    public function show(int $id): Response
    {
        return Inertia::render('Order/OrderCard', ['order' => $this->orderService->getById($id), 'details' => $this->orderService->getOrderItems($id)]);
    }

}
