<?php

namespace App\Services;

use Darryldecode\Cart\Facades\CartFacade;

class CartService
{
    public function index($cookie)
    {
        return CartFacade::session($cookie['laravel_session'])->getContent();
    }

    public function store($cookie, $request)
    {
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

    public function update($cookie, $id, $request)
    {
        CartFacade::session($cookie['laravel_session'])->update($id, [
            'quantity' => ['relative' => false, 'value' => $request['quantity']],
        ]);
    }

    public function destroy($cookie, $id)
    {
        CartFacade::session($cookie['laravel_session'])->remove($id);
    }
}
