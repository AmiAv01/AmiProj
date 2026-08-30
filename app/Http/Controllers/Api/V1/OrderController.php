<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\Cart\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
        private readonly CartService $carts,
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => ['orders' => $this->orders->getByUserId(auth()->id())]]);
    }

    public function store(OrderRequest $request): OrderResource
    {
        $userId = auth()->id();
        $order = $this->orders->createOrder(
            new OrderDTO(OrderStatus::NEW, $userId, $request->validated('comment')),
            $this->carts->getOrCreateUserCart($userId),
        );

        return OrderResource::make($order);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(['data' => [
            'order' => $this->orders->getByIdForUser($id, auth()->id()),
            'details' => $this->orders->getOrderItems($id),
        ]]);
    }
}
