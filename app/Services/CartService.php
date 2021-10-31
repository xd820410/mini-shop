<?php

namespace App\Services;

use Exception;

class CartService
{
    public function addToCart($data)
    {
        //session()->flush();
        if (session()->has('cart.' . 'goods_' . $data['goods_id'])) {
            $quantityNow = session()->get('cart.' . 'goods_' . $data['goods_id'] . '.quantity');
            session()->put('cart.' . 'goods_' . $data['goods_id'] . '.quantity', $quantityNow + $data['quantity']);
            return session()->get('cart');
        }

        session()->put('cart.' . 'goods_' . $data['goods_id'], $data);
        return session()->get('cart');
    }
}
