<?php

namespace App\Http\Controllers\Cart;

use App\DTO\CartDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartFormCreateRequest;
use App\Http\Requests\CartFormUpdateRequest;
use App\Services\CartService;
use App\Services\PriceService;
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

    public function store(CartFormCreateRequest $request)
    {
        return $this->cartService->addToCart(auth()->id(), new CartDTO($request->validated('id'), $request->validated('quantity'), $request->validated('price')));
    }

    public function update(CartFormUpdateRequest $request, int $id): array
    {
        $this->cartService->updateQuantity(auth()->id(), new CartDTO($id, $request->validated('quantity'), 1));
        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }

    public function destroy(int $id): array
    {
        $this->cartService->deleteProductFromCart(auth()->id(), $id);
        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }
}
