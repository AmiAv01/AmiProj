<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Inertia\Inertia;

class OrderAdminController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {

    }

    public function index()
    {
        return Inertia::render('Admin/Orders/OrderList', ['orders' => $this->orderService->getAll()]);
    }
}
