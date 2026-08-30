<?php

namespace App\Http\Controllers\Admin;

use App\DTO\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;

class AdminOrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function update(int $id, UpdateOrderStatusRequest $request): Order
    {
        return $this->orderService->updateOrderStatus(
            $id,
            new OrderDTO($request->validated('status'), auth()->user()->id)
        );

    }
}
