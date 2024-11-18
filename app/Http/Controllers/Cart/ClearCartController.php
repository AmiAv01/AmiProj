<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;

class ClearCartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index(){
        $this->cartService->clearCart(auth()->id());
        return ['items' => $this->cartService->getCartItems(auth()->id())];
    }
}
