<?php

namespace App\Http\Controllers\Admin;

use App\DTO\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AdminOrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {}

    public function index():Response
    {
        return Inertia::render('Admin/Orders/OrderList', ['orders' => $this->orderService->getByStatus()]);
    }

    public function show(int $id):Response
    {
        return Inertia::render('Admin/Orders/OrderCard', ['order' => $this->orderService->getById($id), 'details' => $this->orderService->getOrderItems($id)]);
    }

    public function update(int $id, UpdateOrderStatusRequest $request): Order{
        return $this->orderService->updateOrderStatus($id, new OrderDTO(0, $request->validated('status'), auth()->user()->id));

    }
}
