<?php

namespace App\Http\Controllers\Order;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Exceptions\CartOperationException;
use App\Exceptions\EmptyCartException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\Cart\CartService;
use App\Services\OrderService;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService, protected CartService $cartService) {}

    public function index(): Response
    {
        return Inertia::render('Order/OrderList', ['orders' => $this->orderService->getByUserId(auth()->id())]);
    }

    /**
     * @throws CartOperationException
     * @throws EmptyCartException
     */
    public function store(OrderRequest $request): OrderResource
    {
        $userId = auth()->id();
        $cart = $this->cartService->getOrCreateUserCart($userId);
        $order = $this->orderService->createOrder(new OrderDTO(0, OrderStatus::NEW->value, $userId), $cart);

        return OrderResource::make($order);
    }

    public function show(int $id): Response
    {
        $userId = auth()->id();

        return Inertia::render('Order/OrderCard', [
            'order' => $this->orderService->getByIdForUser($id, $userId),
            'details' => $this->orderService->getOrderItems($id),
        ]);
    }
}
