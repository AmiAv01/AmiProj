<?php

namespace App\Http\Controllers;

use App\Jobs\SendCustomerOrderConfirmedNotification;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;

class ConfirmOrderController extends Controller
{
    public function __construct(private readonly OrderService $orders) {}

    public function __invoke(Order $order): View
    {
        $confirmedNow = $this->orders->confirmOrder($order->getKey());
        $order->refresh();

        if ($confirmedNow) {
            SendCustomerOrderConfirmedNotification::dispatch($order);
        }

        return view('orders.confirmed', [
            'order' => $order,
            'confirmedNow' => $confirmedNow,
        ]);
    }
}
