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
    public function addToCart(AddToCart $request, CartService $cartService)
    {
        try {
            //return $request->input();
            //return $request->session()->all();
            $result = $cartService->addToCart(Arr::except($request->input(), ['_token']));
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
