<?php

namespace App\Services;

use App\DTO\CartDTO;
use App\Models\Cart;
use App\Models\CartItem;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Support\Facades\Log;

final class CartService
{
    public function storeCart(int $userId): Cart
    {
        return Cart::create([
            'user_id' => $userId
        ]);
    }

    public function addToCart(int $userId, CartDTO $dto): CartItem
    {
        $cart = Cart::user($userId)->first();
        return CartItem::create(['cart_id' => $cart->cart_id, 'dt_id' => $dto->productId, 'quantity' => $dto->quantity, 'price' => $dto->productPrice]);
    }

    public function getCartItems(int $userId): array
    {
        $cart = Cart::user($userId)->first();
        $cartItems = $cart->items;
        $details = [];
        $i = 0;
        foreach ($cartItems as $cartItem){
            $details[$i] = array_merge($cartItem->toArray(), $cartItem->product->toArray());
            $i++;
        }
        return $details;
    }

    public function updateQuantity(int $userId, CartDTO $dto){
        $cart = Cart::user($userId)->first();
        $cartProduct = $cart->items->where('dt_id', '=', $dto->productId)->first();
        return $cartProduct->update(['quantity' => $dto->quantity]);
    }

    public function deleteProductFromCart(int $userId, int $productId){
        $cart = Cart::user($userId)->first();
        $cartProduct = $cart->items->where('dt_id', '=', $productId)->first();
        return $cartProduct->delete();
    }

    public function clearCart(int $userId):void{
        $cart = Cart::user($userId)->first();
        $cartItems = $cart->items;
        foreach ($cartItems as $cartItem){
            Log::info($cartItem);
            $cartItem->delete();
        }
    }

    public function getQuantity(int $cartId)
    {

    }

    public function getResultPrice(int $cartId)
    {

    }

}
