<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use App\Services\Cart\CartService;
use App\Services\CurrencyService;
use App\Services\DetailService;
use App\Services\NewsService;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct(
        private readonly DetailService $details,
        private readonly NewsService $news,
        private readonly OrderService $orders,
        private readonly UserService $users,
        private readonly CartService $carts,
        private readonly CurrencyService $currency,
    ) {}

    public function dashboard(): JsonResponse
    {
        return response()->json(['data' => []]);
    }

    public function details(): JsonResponse
    {
        $details = $this->details->getByBrand(12);
        $details->withPath('/admin/resource/details');
        return response()->json(['data' => [
            'details' => $details,
            'brands' => Firm::all(),
        ]]);
    }

    public function news(): JsonResponse
    {
        $news = $this->news->getAll(12);
        $news->withPath('/admin/resource/news');
        return response()->json(['data' => ['news' => $news]]);
    }

    public function orders(): JsonResponse
    {
        $orders = $this->orders->getByStatus();
        $orders->withPath('/admin/resource/orders');
        return response()->json(['data' => ['orders' => $orders]]);
    }

    public function order(int $id): JsonResponse
    {
        return response()->json(['data' => [
            'order' => $this->orders->getById($id),
            'details' => $this->orders->getOrderItems($id),
        ]]);
    }

    public function users(): JsonResponse
    {
        $users = $this->users->getAll(12);
        $users->withPath('/admin/resource/users');
        return response()->json(['data' => ['users' => $users]]);
    }

    public function user(int $id): JsonResponse
    {
        return response()->json(['data' => [
            'user' => $this->users->getById($id),
            'cart' => $this->carts->getCartItemsByUserId($id),
            'orders' => $this->orders->getByUserId($id),
            'formula' => $this->users->getUserFormula($id),
        ]]);
    }

    public function currency(): JsonResponse
    {
        return response()->json(['data' => ['currency' => $this->currency->getCurrency()]]);
    }
}
