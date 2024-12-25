<?php

namespace App\Http\Controllers\Cart;

use App\DTO\CartDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartFormRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {}

    public function index(): Response
    {
        return Inertia::render('Cart/Cart', ['items' => $this->cartService->getCartItems(auth()->id())]);
    }

    public function store(CartFormRequest $request)
    {
        return $this->cartService->addToCart(auth()->id(), new CartDTO($request->validated('id'), $request->validated('quantity')));
    }

    public function update(CartFormRequest $request, int $id): array
    {
        $this->cartService->updateQuantity(auth()->id(), new CartDTO($id, $request->validated('quantity')));
        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }

    public function destroy(int $id): array
    {
        $this->cartService->deleteProductFromCart(auth()->id(), $id);
        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }
}
