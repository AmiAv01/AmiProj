<?php

namespace App\Http\Controllers;

use Darryldecode\Cart\Facades\CartFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cookie = $request->cookie();
        $items = CartFacade::session($cookie['laravel_session'])->getContent();
        Log::info($items);

        return Inertia::render('Cart/Cart', ['items' => $items, 'count' => $items->count()]);
    }

    public function store(Request $request)
    {
        $cookie = $request->cookie();
        if (! CartFacade::session($cookie['laravel_session'])) {
            CartFacade::sesion($cookie['laravel_session']);
        }
        CartFacade::session($cookie['laravel_session'])->add([
            'id' => $request['id'],
            'name' => $request['typec'],
            'price' => 100,
            'quantity' => 1,
            'attributes' => ['fr_code' => $request['fr_code'], 'cargo' => $request['cargo'], 'invoice' => $request['invoice']],
        ]);

        return $request;
    }

    public function update(Request $request)
    {
        $cookie = $request->cookie();
        Log::info($request->query('quantity'));
        CartFacade::session($cookie['laravel_session'])->update($request->query('id'), [
            'quantity' => $request->query('quantity'),
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $cookie = $request->cookie();
        Log::info($id);
        CartFacade::session($cookie['laravel_session'])->remove($id);
        $items = CartFacade::session($cookie['laravel_session'])->getContent();

        return ['items' => $items];
    }
}
