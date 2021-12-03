<?php

namespace App\Services;

use Exception;
use App\Repositories\CartRepository;
use App\Repositories\GoodsRepository;

class CartService
{

    public function deleteItemFromUserCart($userId, $goodsId, CartRepository $cartRepository)
    {
        $userCart = $cartRepository->getByUserId($userId);
        if (empty($userCart)) {
            throw new Exception('User does not exist.');
        }

        $payload = $userCart['payload'];
        if (isset($payload['goods_' . $goodsId])) {
            unset($payload['goods_' . $goodsId]);
        }

        $data = [];
        $data['payload'] = $payload;
        $result = $cartRepository->updateById($userCart['id'], $data);
        if (empty($result)) {
            throw new Exception('Fail to update.');
        }

        return $result;
    }

    public function deleteItemFromSessionCart($goodsId)
    {
        if (session()->has('cart.' . 'goods_' . $goodsId)) {
            session()->forget('cart.' . 'goods_' . $goodsId);
        }

        return session()->get('cart');
    }

    public function fillItemDataInCart(Array $cart, GoodsRepository $goodsRepository)
    {
        /**
         * Prevent N+1 problem
         */
        $cartItemIdList = [];
        foreach ($cart as $itemKey => $item) {
            $cartItemIdList[] = $item['goods_id'];
        }

        $existingGoods = $goodsRepository->getByIds($cartItemIdList)->toArray();
        $goods = [];
        foreach ($existingGoods as $eachGoods) {
            $goods['goods_' . $eachGoods['id']] = $eachGoods;
        }

        $cartAfterFillingItemData = [];
        foreach ($cart as $itemKey => $item) {
            $thisItem = [];
            if (isset($goods[$itemKey]) && !empty($goods[$itemKey])) {
                $thisItem = array_merge($cart[$itemKey], $goods[$itemKey]);
                unset($thisItem['id']);
                unset($thisItem['created_at']);
                unset($thisItem['updated_at']);
                $cartAfterFillingItemData[$itemKey] = $thisItem;
            }
        }

        return $cartAfterFillingItemData;
    }

    public function getUserCart($userId, CartRepository $cartRepository)
    {
        $userCart = $cartRepository->getByUserId($userId);

        return $userCart['payload'];
    }

    public function getSessionCart()
    {
        return session()->get('cart');
    }

    public function updateUserCart($userId, $cartItemData, CartRepository $cartRepository)
    {
        $userCart = $cartRepository->getByUserId($userId);
        if (empty($userCart)) {
            throw new Exception('User does not exist.');
        }

        $payload = $userCart['payload'];
        $payload['goods_' . $cartItemData['goods_id']] = $cartItemData;

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
        session()->put('cart.' . 'goods_' . $data['goods_id'] . '.quantity', $data['quantity']);

        return session()->get('cart');
    }
}
