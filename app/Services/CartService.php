<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Support\Facades\Log;

final class CartService
{

    public function storeCart(int $userId): Cart
    {
        $cart = Cart::create([
            'user_id' => $userId
        ]);
        return $cart;
    }

    public function addToCart(int $userId, int $productId): CartItem
    {
        $cart = Cart::where('user_id', '=', $userId)->first();
        return CartItem::create(['cart_id' => $cart->cart_id, 'dt_id' => $productId, 'quantity' => 1]);
    }

    public function getCartItems(int $userId): array
    {
        $cart = Cart::where('user_id', '=', $userId)->first();
        $cartItems = $cart->items;
        $details = [];
        $i = 0;
        foreach ($cartItems as $cartItem){
            $details[$i] = array_merge($cartItem->toArray(), $cartItem->product->toArray());
            $i++;
        }
        return $details;
    }

    public function updateQuantity(int $userId, int $productId, int $quantity){
        $cart = Cart::where('user_id', '=', $userId)->first();
        $cartProduct = $cart->items->where('dt_id', '=', $productId)->first();
        return $cartProduct->update(['quantity' => $quantity]);
    }

    public function deleteProductFromCart(int $userId, int $productId){
        $cart = Cart::where('user_id', '=', $userId)->first();
        $cartProduct = $cart->items->where('dt_id', '=', $productId)->first();
        return $cartProduct->delete();
    }

    public function clearCart(int $userId):void{
        $cart = Cart::where('user_id', '=', $userId)->first();
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
