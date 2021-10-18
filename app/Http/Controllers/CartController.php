<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AddToCart;
use App\Services\CartService;
use App\Services\GoodsService;
use Exception;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function addToCart(AddToCart $request, CartService $cartService, GoodsService $goodsService)
    {
        try {
            //return $request->input();
            return $request->session()->all();
            return $cartService->addToCart(Arr::except($request->input(), ['_token']));
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => 'Goods ' . $request->input('goods_id') . ' is in your cart now.',
            ];

            return response()->json($returnMessage, Response::HTTP_CREATED);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }
}
