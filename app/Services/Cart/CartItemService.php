<?php

namespace App\Services\Cart;

use App\DTO\CartDTO;
use App\Exceptions\CartItemAlreadyExistException;
use App\Exceptions\CartItemNotFoundException;
use App\Models\Cart;
use App\Models\CartItem;

final class CartItemService
{
    public function addItemToCart(int $cartId, CartDTO $dto): ?CartItem
    {
        if ($this->itemExists($cartId, $dto->productId)) {
            throw new CartItemAlreadyExistException($cartId, $dto->productId);
        }
        return CartItem::create(['cart_id' => $cartId, 'dt_id' => $dto->productId, 'quantity' => $dto->quantity, 'price' => $dto->productPrice]);
    }

    public function updateItemQuantity(Cart $cart, CartDTO $dto){
        $cartProduct = $cart->items->where('dt_id', '=', $dto->productId)->first();
        if (!$cartProduct) {
            throw new CartItemNotFoundException($cart->id, $dto->productId);
        }
        return $cartProduct->update(['quantity' => $dto->quantity]);
    }

    public function deleteItemFromCart(Cart $cart, int $productId){
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
