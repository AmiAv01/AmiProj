<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {

    }

    public function index(Request $request): Response
    {
        return Inertia::render('Cart/Cart', ['items' => $this->cartService->getCartItems(auth()->id())]);
    }

    public function store(Request $request)
    {
        return $this->cartService->addToCart(auth()->id(), $request->id);
    }

    public function update(Request $request, int $id): array
    {
        $this->cartService->updateQuantity(auth()->id(), $id, $request->quantity);
        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }

    public function destroy(int $id): array
    {
        $this->cartService->deleteProductFromCart(auth()->id(), $id);

        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }
}
