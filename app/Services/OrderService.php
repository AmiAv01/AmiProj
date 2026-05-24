<?php

namespace App\Services;

use App\DTO\OrderDTO;
use App\Enums\OrderStatus;
use App\Events\OrderCreated;
use App\Exceptions\EmptyCartException;
use App\Exceptions\InvalidOrderStatusException;
use App\Exceptions\OrderNotFoundException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class OrderService
{
    public function getAll(int $perPage): LengthAwarePaginator
    {
        return Order::join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.status', 'order.created_at', 'order.total_price', 'user.name', 'user.email'])->paginate($perPage);
    }

    public function getByUserId(int $userId): Collection
    {
        return Order::where('created_by', '=', $userId)->join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.created_at', 'order.status', 'order.total_price', 'user.name', 'user.email'])->get();
    }

    public function createOrder(OrderDTO $dto, Cart $cart): Order
    {
        $cart->loadMissing('items');
        if ($cart->items->isEmpty()) {
            throw new EmptyCartException;
        }

        return DB::transaction(function () use ($dto, $cart): Order {
            $orderTotal = $cart->items->sum(fn (CartItem $item): int => (int) $item->price * $item->quantity);
            $order = Order::create([
                'total_price' => $orderTotal,
                'status' => $dto->status,
                'created_by' => $dto->userId,
                'updated_by' => $dto->userId,
            ]);
            $this->createOrderItems($cart, $order);
            event(new OrderCreated($this->getOrderWithRelations($order->id)));

            return $order;
        });
    }

    private function createOrderItems(Cart $cart, Order $order): void
    {
        $orderItems = $cart->items->map(function (CartItem $item) use ($order) {
            return [
                'order_id' => $order->id,
                'detail_id' => $item->dt_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });
        $order->orderItems()->insert($orderItems->toArray());
    }

    private function getOrderWithRelations(int $orderId): Order
    {
        return Order::with([
            'user',
            'orderItems.detail',
        ])->findOrFail($orderId);
    }

    public function updateOrderStatus(int $id, OrderDTO $dto): Order
    {
        $order = Order::query()->findOrFail($id);
        if (! in_array($dto->status, OrderStatus::values(), true)) {
            throw new InvalidOrderStatusException($dto->status);
        }
        $order->update(['status' => $dto->status]);

        return $order;
    }

    public function getById(int $id): Order
    {
        $order = Order::where('order.id', '=', $id)->join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.status', 'order.created_at', 'order.total_price', 'user.name', 'user.email'])->first();
        if (! $order) {
            throw new OrderNotFoundException($id);
        }

        return $order;
    }

    public function getByIdForUser(int $id, int $userId): Order
    {
        $order = Order::where('order.id', '=', $id)
            ->where('order.created_by', '=', $userId)
            ->join('user', 'order.created_by', '=', 'user.id')
            ->select(['order.id', 'order.status', 'order.created_at', 'order.total_price', 'user.name', 'user.email'])
            ->first();
        if (! $order) {
            throw new OrderNotFoundException($id);
        }

        return $order;
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

    public function getBySearching(string $search, int $perPage = 12): LengthAwarePaginator
    {
        return Order::join('user', 'order.created_by', '=', 'user.id')
            ->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->select('user.email', 'user.name', 'order.id', 'order.total_price', 'order.status', 'order.created_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
