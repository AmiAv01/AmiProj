<?php

namespace App\Http\Controllers\Order;

use App\DTO\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService, protected CartService $cartService) {}

    public function index(): Response
    {
        return Inertia::render('Order/OrderList', ['orders' => $this->orderService->getByUserId(auth()->id())]);
    }

    public function store(OrderRequest $request): OrderResource
    {
        $userId = auth()->id();
        Log::info($userId);
        $cart = $this->cartService->getOrCreateUserCart($userId);
        $order = $this->orderService->createOrder(new OrderDTO($request->validated('totalPrice'), 'Новый', $userId), $cart);
        return OrderResource::make($order);
    }

    public function show(int $id): Response
    {
        return Inertia::render('Order/OrderCard', ['order' => $this->orderService->getById($id), 'details' => $this->orderService->getOrderItems($id)]);
    }
}
