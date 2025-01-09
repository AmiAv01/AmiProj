<?php

namespace App\Services;

use App\DTO\OrderDTO;
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
        return Order::join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.status', 'order.created_at', 'order.total_price', 'user.name', 'user.email'])->paginate($perPage);
    }

    public function getByUserId(string $userId): Collection
    {
        return Order::where('created_by', '=', $userId)->join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.created_at', 'order.status', 'order.total_price','user.name', 'user.email'])->get();
    }

    public function createOrder(OrderDTO $dto):Order
    {
        $order = Order::create(['total_price' => $dto->totalPrice, 'status' => 'Новый', 'created_by' => $dto->userId, 'updated_by' => $dto->userId]);
        $cart =  Cart::user($dto->userId)->first();
        $cartItems = $cart->items;
        foreach ($cartItems as $item) {
            $order->items()->create([
                'detail_id' => $item->product['dt_id'],
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
            ]);
        }
        return $order;
    }

    public function getById(int $id): Collection
    {
        return Order::where('order.id', '=', $id)->join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.status', 'order.created_at', 'order.total_price', 'user.name', 'user.email'])->get();
    }

    public function getOrderItems(int $id): Collection
    {
        return OrderItem::where('order_id', '=', $id)->join('detail', 'order_item.detail_id', '=', 'detail.dt_id')
            ->select(['detail.dt_typec', 'detail.dt_invoice', 'detail.dt_cargo', 'detail.fr_code', 'order_item.unit_price', 'order_item.quantity'])->get();
    }

    public function getByStatus(): LengthAwarePaginator
    {
        return QueryBuilder::for(Order::class)->allowedFilters(AllowedFilter::exact('id', 'status'))->join('user', 'order.created_by', '=', 'user.id')
            ->select(['user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at'])->paginate(12)->withQueryString();
    }

    public function getBySearching(string $search): LengthAwarePaginator
    {
        Log::info(strval($search));

        return Order::join('user', 'order.created_by', '=', 'user.id')->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")
            ->select('user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at')->paginate(12)->withQueryString();
    }

}
