<?php

namespace App\Http\Controllers\Cart;

use App\DTO\CartDTO;
use App\Exceptions\CartItemAlreadyExistException;
use App\Exceptions\CartOperationException;
use App\Exceptions\PriceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartFormCreateRequest;
use App\Http\Requests\CartFormUpdateRequest;
use App\Models\Detail;
use App\Services\Cart\CartItemService;
use App\Services\Cart\CartService;
use App\Services\DetailService;
use App\Services\PriceService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CartItemService $cartItemService,
        protected PriceService $priceService,
        protected DetailService $detailService,
    ) {}

    public function index(): Response
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());

        return Inertia::render('Cart/Cart', ['items' => $this->cartService->getCartItems($cart)]);
    }

    /**
     * @throws CartOperationException
     * @throws PriceNotFoundException
     * @throws CartItemAlreadyExistException
     */
    public function store(CartFormCreateRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $productId = (int) $request->validated('id');
        $detail = $this->detailService->getById($productId);
        $price = (string) $this->priceService->getPrice($detail->dt_code, $userId);
        $this->cartItemService->addItemToCart($cart->id, new CartDTO($productId, (int) $request->validated('quantity', 1), $price));

        return response()->json([
            'items' => $this->cartService->getCartItems($cart),
            'newCartCount' => $this->cartService->getCartQuantity($cart),
        ]);
    }

    public function update(CartFormUpdateRequest $request, string $id): JsonResponse
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $this->cartItemService->updateItemQuantity($cart, new CartDTO($id, $request->validated('quantity'), '1'));

        return response()->json([
            'items' => $this->cartService->getCartItems($cart),
            'newCartCount' => $this->cartService->getCartQuantity($cart),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $cart = $this->cartService->getOrCreateUserCart(auth()->id());
        $this->cartItemService->deleteItemFromCart($cart, $id);

        return response()->json([
            'items' => $this->cartService->getCartItems($cart),
            'newCartCount' => $this->cartService->getCartQuantity($cart),
        ]);
    }
}
