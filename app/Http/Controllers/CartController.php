<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\AddToCart;
use App\Services\CartService;
use App\Services\GoodsService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    public function addItemToUserCart(AddToCart $request)
    {
        try {
            $userCartExisting = App::call([new CartService, 'checkUserCartExisting'], ['userId' => Auth::user()->id]);

            if ($userCartExisting === true) {
                $result = App::call([new CartService, 'updateUserCart'], [
                    'userId' => Auth::user()->id,
                    'cartItemData' => Arr::except($request->input(), ['_token'])
                ]);
                //$httpStatusCode = Response::HTTP_CREATED;
                $httpStatusCode = Response::HTTP_NO_CONTENT;
            } else {
                $result = App::call([new CartService, 'createUserCart'], [
                    'userId' => Auth::user()->id,
                    'cartItemData' => Arr::except($request->input(), ['_token'])
                ]);
                $httpStatusCode = Response::HTTP_CREATED;
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $result,
            ];

            return response()->json($returnMessage, $httpStatusCode);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function addItemToSessionCart(AddToCart $request, CartService $cartService)
    {
        try {
            $checkItemInCart = $cartService->checkItemInSessionCart($request->input('goods_id'));

            if ($checkItemInCart === true) {
                $result = $cartService->updateItemInSessionCart(Arr::except($request->input(), ['_token']));
            } else {
                $result = $cartService->addItemToSessionCart(Arr::except($request->input(), ['_token']));
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
