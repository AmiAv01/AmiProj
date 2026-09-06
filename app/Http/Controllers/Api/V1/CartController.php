<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\CartDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartFormCreateRequest;
use App\Http\Requests\CartFormUpdateRequest;
use App\Services\Cart\CartItemService;
use App\Services\Cart\CartService;
use App\Services\DetailService;
use App\Services\PriceService;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $carts,
        private readonly CartItemService $items,
        private readonly PriceService $prices,
        private readonly DetailService $details,
    ) {}

    public function index(): JsonResponse
    {
        return $this->response();
    }

    public function store(CartFormCreateRequest $request): JsonResponse
    {
        $cart = $this->carts->getOrCreateUserCart(auth()->id());
        $productId = (int) $request->validated('id');
        $detail = $this->details->getById($productId);
        $price = (string) $this->prices->getPrice($detail->dt_code, auth()->id());
        $this->items->addItemToCart($cart->id, new CartDTO(
            $productId,
            (int) $request->validated('quantity', config('cart.quantity.default')),
            $price,
        ));

        return $this->response();
    }

    public function update(CartFormUpdateRequest $request, int $id): JsonResponse
    {
        $cart = $this->carts->getOrCreateUserCart(auth()->id());
        $this->items->updateItemQuantity($cart, new CartDTO($id, (int) $request->validated('quantity'), '1'));

        return $this->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $cart = $this->carts->getOrCreateUserCart(auth()->id());
        $this->items->deleteItemFromCart($cart, $id);

        return $this->response();
    }

    public function clear(): JsonResponse
    {
        $cart = $this->carts->getOrCreateUserCart(auth()->id());
        $this->carts->clearCart($cart);

        return $this->response();
    }

    private function response(): JsonResponse
    {
        $cart = $this->carts->getOrCreateUserCart(auth()->id());

        return response()->json(['data' => [
            'items' => $this->carts->getCartItems($cart),
            'cartCount' => $this->carts->getCartQuantity($cart),
        ]]);
    }
}
