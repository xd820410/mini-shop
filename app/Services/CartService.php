<?php

namespace App\Services;

use Exception;
use App\Repositories\CartRepository;

class CartService
{
    public function updateUserCart($userId, $cartItemData, CartRepository $cartRepository)
    {
        $userCart = $cartRepository->getByUserId($userId);
        if (empty($userCart)) {
            throw new Exception('User does not exist.');
        }

        $payload = $userCart['payload'];
        if (isset($userCart['payload']['goods_' . $cartItemData['goods_id']]) && !empty($userCart['payload']['goods_' . $cartItemData['goods_id']])) {
            $payload['goods_' . $cartItemData['goods_id']]['quantity'] += $cartItemData['quantity'];
        } else {
            $cartItemData['quantity'] = (int) $cartItemData['quantity'];
            $payload['goods_' . $cartItemData['goods_id']] = $cartItemData;
        }

        $data = [];
        $data['payload'] = $payload;
        $result = $cartRepository->updateById($userCart['id'], $data);
        if (empty($result)) {
            throw new Exception('Fail to update.');
        }

        return $result;
    }

    public function createUserCart($userId, $cartItemData, CartRepository $cartRepository)
    {
        $data = [];
        $data['user_id'] = $userId;
        $data['payload'] = [];
        $data['payload']['goods_' . $cartItemData['goods_id']] = [];
        $data['payload']['goods_' . $cartItemData['goods_id']] = $cartItemData;

        $result = $cartRepository->create($data);
        if (empty($result)) {
            throw new Exception('Fail to create.');
        }

        return $result;
    }

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
