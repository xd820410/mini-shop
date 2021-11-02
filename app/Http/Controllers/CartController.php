<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\AddToCart;
use App\Services\CartService;
use App\Services\GoodsService;
use Exception;
use Illuminate\Support\Arr;
use App\Models\User;

class CartController extends Controller
{
    public function test()
    {
        return User::with('permissions')->get();
    }

    public function addItemToCart(AddToCart $request, CartService $cartService)
    {
        try {
            $checkItemInCart = $cartService->checkItemInCart(Arr::except($request->input(), ['_token']));

            if ($checkItemInCart === true) {
                $result = $cartService->updateItemInCart(Arr::except($request->input(), ['_token']));
            } else {
                $result = $cartService->addItemToCart(Arr::except($request->input(), ['_token']));
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $result,
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
