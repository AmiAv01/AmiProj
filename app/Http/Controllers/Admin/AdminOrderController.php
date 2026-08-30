<?php

namespace App\Http\Controllers\Admin;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class AdminOrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function update(int $id, UpdateOrderStatusRequest $request): OrderResource
    {
        return OrderResource::make($this->orderService->updateOrderStatus(
            $id,
            new OrderDTO(OrderStatus::from($request->validated('status')), auth()->id())
        ));
    }
}
