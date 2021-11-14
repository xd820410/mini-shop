<?php

namespace App\Services;

use Exception;

class CartService
{
    public function addItemToCart($data)
    {
        $data['quantity'] = (int) $data['quantity'];
        session()->put('cart.' . 'goods_' . $data['goods_id'], $data);
        
        return session()->get('cart');
    }

    public function checkItemInCart($data)
    {
        if (session()->has('cart.' . 'goods_' . $data['goods_id'])) {
            return true;
        }

        return false;
    }

    public function updateItemInCart($data)
    {
        $quantityNow = session()->get('cart.' . 'goods_' . $data['goods_id'] . '.quantity');
        session()->put('cart.' . 'goods_' . $data['goods_id'] . '.quantity', $quantityNow + $data['quantity']);

        return session()->get('cart');
    }
}
