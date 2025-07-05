<?php

namespace App\Http\Controllers\Cart;

use App\DTO\CartDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartFormCreateRequest;
use App\Http\Requests\CartFormUpdateRequest;
use App\Models\CartItem;
use App\Services\Cart\CartItemService;
use App\Services\Cart\CartService;
use \Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService, protected CartItemService $cartItemService) {}

    public function index(): Response
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        return Inertia::render('Cart/Cart', ['items' => $this->cartService->getCartItems($cart)]);
    }

    public function store(CartFormCreateRequest $request): JsonResponse
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $this->cartItemService->addItemToCart($cart->id, new CartDTO($request->validated('id'), $request->validated('quantity'), $request->validated('price')));
        return response()->json([
            'items' => $this->cartService->getCartItems($cart),
            'newCartCount' => $this->cartService->getCartQuantity($cart)
        ]);
    }

    public function update(CartFormUpdateRequest $request, string $id): JsonResponse
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $this->cartItemService->updateItemQuantity($cart, new CartDTO($id, $request->validated('quantity'), '1'));
        return response()->json([
            'items' => $this->cartService->getCartItems($cart),
            'newCartCount' => $this->cartService->getCartQuantity($cart)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $this->cartItemService->deleteItemFromCart($cart, $id);
        return response()->json([
            'items' => $this->cartService->getCartItems($cart),
            'newCartCount' => $this->cartService->getCartQuantity($cart)
        ]);
    }
}
