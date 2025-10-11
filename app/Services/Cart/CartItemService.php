<?php

namespace App\Services\Cart;

use App\DTO\CartDTO;
use App\Exceptions\CartItemAlreadyExistException;
use App\Exceptions\CartItemNotFoundException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Detail;
use App\Services\PriceService;
use App\Validation\PriceValidator;

final class CartItemService
{

    public function __construct(protected PriceService $priceService, protected PriceValidator $priceValidator) {}

    public function addItemToCart(CartDTO $dto): ?CartItem
    {
        $cartId = $dto->cart->id;
        if ($this->itemExists($cartId, $dto->productId)) {
            throw new CartItemAlreadyExistException($cartId, $dto->productId);
        }
        $detail = Detail::where('dt_id', $dto->productId)->select('dt_code')->first();
        $price = $this->priceService->getPrice($detail->dt_code, auth()->id());
        $this->priceValidator->validate($price, $dto->productId);

        return CartItem::create(['cart_id' => $cartId, 'dt_id' => $dto->productId, 'quantity' => $dto->quantity, 'price' => $price]);
    }

    public function updateItemQuantity(CartDTO $dto)
    {
        $cartProduct = $dto->cart->items->where('dt_id', '=', $dto->productId)->first();
        if (!$cartProduct) {
            throw new CartItemNotFoundException($dto->cart->id, $dto->productId);
        }
        return $cartProduct->update(['quantity' => $dto->quantity]);
    }

    public function deleteItemFromCart(Cart $cart, int $productId)
    {
        $cartProduct = $cart->items->where('dt_id', '=', $productId)->first();
        if (!$cartProduct) {
            throw new CartItemNotFoundException($cart->id, $productId);
        }
        return $cartProduct->delete();
    }

    public function itemExists(int $cartId, int $productId): bool
    {
        return CartItem::where('cart_id', $cartId)->where('dt_id', $productId)->exists();
    }
}
