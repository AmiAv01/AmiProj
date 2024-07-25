<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderService
{
    public function getAll()
    {
        return Order::join('user', 'order.created_by', '=', 'user.id')->paginate(12);
    }

    public function getByUserId($userId)
    {
        return Order::where('created_by', '=', $userId)->get();
    }

    public function createOrder($request, $userId)
    {
        $cookie = $request->cookie();
        $order = Order::create(['total_price' => $request['total_price'], 'status' => 'Новый', 'session_id' => $cookie['laravel_session'], 'created_by' => $userId, 'updated_by' => $userId]);
        $items = CartFacade::session($cookie['laravel_session'])->getContent();
        foreach ($items as $item) {
            $order->items()->create([
                'detail_id' => $item->id,
                'quantity' => $item->quantity,
                'unit_price' => $item->quantity * $item->price,
            ]);
        }
    }

    public function getById($id)
    {
        return Order::find($id);
    }

    public function getOrderItems($id)
    {
        return OrderItem::where('order_id', '=', $id)->join('detail', 'order_item.detail_id', '=', 'detail.dt_id')->get();
    }

    public function getByStatus()
    {
        return QueryBuilder::for(Order::class)->allowedFilters(AllowedFilter::exact('id', 'status'))->join('user', 'order.created_by', '=', 'user.id')
            ->select('user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at')->paginate(12)->withQueryString();
    }

    public function getBySearching(string $search)
    {
        Log::info(strval($search));

        return Order::join('user', 'order.created_by', '=', 'user.id')->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%")
            ->select('user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at')->paginate(12)->withQueryString();
    }

    public function update()
    {
    }
}
