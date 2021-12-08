<?php

namespace App\Services;

use Exception;
use App\Repositories\CartRepository;
use App\Repositories\GoodsRepository;
use App\Models\Discount;

class CartService
{
    /**
     * $cart sample:
     * Variable $cart trans to Array already. It's just for reading construction.
     * {"goods_158":{"goods_id":"158","quantity":1,"title":"\u6d77\u81bd\u677e\u9732\u7092\u98ef","description":null,"price":777,"image_path":"\/storage\/images\/goods\/mmBB1636561107.png","discount":[]}}
     * 
     * $effectiveDiscount sample:
     * [{"id":1,"type":1,"title":"\u6bcf\u5169\u4ef6\u516b\u6298","payload":{"affected":[158],"threshold":2,"discount_type":"percent","discount_value":20},"start_at":"2021-12-05 11:17:36","end_at":"2024-12-06 11:17:36","created_at":"2021-12-05T03:17:36.000000Z","updated_at":null}]
     */
    public function calculateDiscount(Array $cart, $effectiveDiscount)
    {
        foreach ($effectiveDiscount as $eachDiscount) {
            switch ($eachDiscount['type']) {
                //某商品滿件折
                case Discount::type_single_goods_quantity_threshold:
                    if (empty($eachDiscount['payload'])) {
                        break;
                    }

                    foreach ($eachDiscount['payload']['affected'] as $affectedGoodsId) {
                        if (!isset($cart['goods_' . $affectedGoodsId])) {
                            continue;
                        }

                        $affectedGoodsQuantityInCart = $cart['goods_' . $affectedGoodsId]['quantity'];
                        $quantityThreshold = $eachDiscount['payload']['threshold'];
                        //買超過門檻
                        if ($affectedGoodsQuantityInCart >= $quantityThreshold) {
                            $fittedQuantity = floor($affectedGoodsQuantityInCart / $quantityThreshold) * $quantityThreshold;

                            switch ($eachDiscount['payload']['discount_type']) {
                                case 'percent':
                                    //無條件捨去(售價 * (discount_value / 100)) * 符合優惠數 * -1
                                    $discount = floor($cart['goods_' . $affectedGoodsId]['price'] * ($eachDiscount['payload']['discount_value'] / 100)) * $fittedQuantity * -1;
                                    break;
                                case 'value':
                                    //discount_value * 符合優惠數 * -1
                                    $discount = $eachDiscount['payload']['discount_value'] * $fittedQuantity * -1;
                                    break;
                                default:
                                    $discount = 0;
                                    break;
                            }

                            if ($discount === 0) {
                                break;
                            }
                            $discountData = [];
                            $discountData['title'] = $eachDiscount['title'];
                            $discountData['discount'] = $discount;
                            $cart['goods_' . $affectedGoodsId]['discount'][] = $discountData;
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        return $cart;
    }

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
                $thisItem['discount'] = [];
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
