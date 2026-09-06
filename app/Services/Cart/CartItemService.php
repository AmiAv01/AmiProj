<?php

namespace App\Services\Cart;

use App\DTO\CartDTO;
use App\Exceptions\CartItemAlreadyExistException;
use App\Exceptions\CartItemNotFoundException;
use App\Models\Cart;
use App\Models\CartItem;

final class CartItemService
{
    public function addItemToCart(int $cartId, CartDTO $dto): CartItem
    {
        $item = CartItem::query()->firstOrCreate(
            ['cart_id' => $cartId, 'dt_id' => $dto->productId],
            ['quantity' => $dto->quantity, 'price' => $dto->productPrice],
        );

        if (! $item->wasRecentlyCreated) {
            throw new CartItemAlreadyExistException($cartId, $dto->productId);
        }

        return $item;
    }

    public function updateItemQuantity(Cart $cart, CartDTO $dto): bool
    {
        $cartProduct = $cart->items()->where('dt_id', $dto->productId)->first();
        if (! $cartProduct) {
            throw new CartItemNotFoundException($cart->id, $dto->productId);
        }

        return $cartProduct->update(['quantity' => $dto->quantity]);
    }

    public function deleteItemFromCart(Cart $cart, int $productId): bool
    {
        $cartProduct = $cart->items()->where('dt_id', $productId)->first();
        if (! $cartProduct) {
            throw new CartItemNotFoundException($cart->id, $productId);
        }

        return $cartProduct->delete();
    }
}
