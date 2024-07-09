<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {

    }

    public function index(Request $request)
    {
        return Inertia::render('Cart/Cart', ['items' => $this->cartService->index($request->cookie())]);
    }

    public function store(Request $request)
    {
        return $this->cartService->store($request->cookie(), $request);
    }

    public function update(Request $request, int $id)
    {
        $this->cartService->update($request->cookie(), $id, $request);

        return ['items' => $this->cartService->index($request->cookie())];
    }

    public function destroy(Request $request, int $id)
    {
        $this->cartService->destroy($request->cookie(), $id);

        return ['items' => $this->cartService->index($request->cookie())];
    }
}
