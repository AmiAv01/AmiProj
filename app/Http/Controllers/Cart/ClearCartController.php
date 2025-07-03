<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;

class ClearCartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function __invoke(): JsonResponse
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $this->cartService->clearCart($cart);
        return response()->json([
            'items' => [],
            'newCartCount' => '0'
        ]);
    }
}
