<?php

namespace App\Services\Cart;

use App\DTO\CartDTO;
use App\Exceptions\CartNotFoundException;
use App\Exceptions\CartOperationException;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Log;

final class CartService
{
    public function getOrCreateUserCart(int $userId): Cart
    {
        try{
            return Cart::firstOrCreate(['user_id' => $userId]);
        } catch (\Exception $e) {
            Log::error("Failed to get or create cart for user {$userId}", ['error' => $e]);
            throw new CartOperationException("Failed to get or create cart: " . $e->getMessage());
        }

    }

    public function getCartItems(Cart $cart): array
    {
        if (!$cart->exists){
            throw new CartNotFoundException($cart->user_id);
        }
        try{
            return $cart->items()->with('detail')->get()
                ->map(function ($item) {
                    return array_merge($item->toArray(), $item->detail->toArray());
                })->toArray();
        } catch (\Exception $e) {
            Log::error("Failed to get cart items for cart {$cart->id}", ['error' => $e]);
            throw new CartOperationException("Failed to get cart items: " . $e->getMessage());
        }

    }

    public function clearCart(Cart $cart):void{
        $cart->items()->delete();
    }

}
