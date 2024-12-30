<?php

namespace App\Http\Controllers\Order;

use App\DTO\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {}

    public function index(): Response
    {
        $userId = auth()->check() ? auth()->user()->id : null;
        return Inertia::render('Order/OrderList', ['orders' => $this->orderService->getByUserId($userId)]);
    }

    public function store(OrderRequest $request): Order
    {
        $order = $this->orderService->createOrder(new OrderDTO($request->validated('totalPrice'), auth()->id()));
        return OrderResource::make($order);
    }

    public function show(int $id): Response
    {
        return Inertia::render('Order/OrderCard', ['order' => $this->orderService->getById($id), 'details' => $this->orderService->getOrderItems($id)]);
    }

}
