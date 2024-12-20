<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class OrderService
{
    public function getAll($perPage): LengthAwarePaginator
    {
        return Order::join('user', 'order.created_by', '=', 'user.id')->paginate($perPage);
    }

    public function getByUserId(string $userId): Collection
    {
        return Order::where('created_by', '=', $userId)->get();
    }

    public function createOrder(int $userId, int $price):Order
    {
        $order = Order::create(['total_price' => $price, 'status' => 'Новый', 'created_by' => $userId, 'updated_by' => $userId]);
        $cart =  Cart::where('user_id', '=', $userId)->first();
        $cartItems = $cart->items;
        foreach ($cartItems as $item) {
            $order->items()->create([
                'detail_id' => $item->product['dt_id'],
                'quantity' => $item->quantity,
                'unit_price' => $item->quantity * $item->price,
            ]);
        }
        return $order;
    }

    public function getById(int $id): Collection
    {
        return Order::where('order.id', '=', $id)->join('user', 'order.created_by', '=', 'user.id')->get();
    }

    public function getOrderItems(int $id): Collection
    {
        return OrderItem::where('order_id', '=', $id)->join('detail', 'order_item.detail_id', '=', 'detail.dt_id')->get();
    }

    public function getByStatus(): LengthAwarePaginator
    {
        return QueryBuilder::for(Order::class)->allowedFilters(AllowedFilter::exact('id', 'status'))->join('user', 'order.created_by', '=', 'user.id')
            ->select('user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at')->paginate(12)->withQueryString();
    }

    public function getBySearching(string $search): LengthAwarePaginator
    {
        Log::info(strval($search));

        return Order::join('user', 'order.created_by', '=', 'user.id')->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")
            ->select('user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at')->paginate(12)->withQueryString();
    }

}
