<?php

namespace App\Services;

use Exception;
use App\Repositories\CartRepository;

class CartService
{
    public function checkUserCartExisting($userId, CartRepository $cartRepository)
    {
        $userCart = $cartRepository->getByUserId($userId);

        if (empty($userCart)) {
            return false;
        }
        return true;
    }

    public function mergeSessionCart($userId, $sessionCart, CartRepository $cartRepository)
    {
        $userCart = $cartRepository->getByUserId($userId);
        
        if (empty($userCart)) {
            return $cartRepository->create([
                'user_id' => $userId,
                'payload' => $sessionCart
            ]);
        }

        $mergedCart = array_merge($userCart['payload'], $sessionCart);
        foreach($userCart['payload'] as $userCartItemKey => $userCartItem) {
            foreach($sessionCart as $sessionCartItemKey => $sessionCartItem) {
                if ($userCartItemKey === $sessionCartItemKey) {
                    $quantity = $userCartItem['quantity'] + $sessionCartItem['quantity'];
                    $mergedCart[$userCartItemKey]['quantity'] = $quantity;
                }
            }
        }
        
        return $cartRepository->updateById(
            $userCart['id'],
            [
                'user_id' => $userId,
                'payload' => $mergedCart
            ]
        );
    }

    public function addItemToSessionCart($data)
    {
        $data['quantity'] = (int) $data['quantity'];
        session()->put('cart.' . 'goods_' . $data['goods_id'], $data);
        
        return session()->get('cart');
    }

    public function checkItemInSessionCart($goodsId)
    {
        if (session()->has('cart.' . 'goods_' . $goodsId)) {
            return true;
        }

        return false;
    }

    public function updateItemInSessionCart($data)
    {
        $quantityNow = session()->get('cart.' . 'goods_' . $data['goods_id'] . '.quantity');
        session()->put('cart.' . 'goods_' . $data['goods_id'] . '.quantity', $quantityNow + $data['quantity']);

        return session()->get('cart');
    }
}
