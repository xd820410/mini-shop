<?php

namespace App\Services;

use Exception;

class CartService
{
    public function addToCart($data)
    {
        if (session()->has('cart.' . 'goods_' . $data['goods_id'])) {
            return 'update';
        }

        session()->push('cart.' . 'goods_' . $data['goods_id'], $data);
        return 'create';
    }
}
