<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\AddToCart;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    public function getUserCart()
    {
        try {
            $cart = App::call([new CartService, 'getUserCart'], ['userId' => Auth::user()->id]);
            if (!empty($cart)) {
                $cart = App::call([new CartService, 'fillItemDataInCart'], ['cart' => $cart]);
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $cart,
            ];

            return response()->json($returnMessage, Response::HTTP_OK);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function getSessionCart(CartService $cartService)
    {
        try {
            $cart = $cartService->getSessionCart();
            if (!empty($cart)) {
                $cart = App::call([new CartService, 'fillItemDataInCart'], ['cart' => $cart]);
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $cart,
            ];

            return response()->json($returnMessage, Response::HTTP_OK);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function addItemToUserCart(AddToCart $request)
    {
        try {
            $userCartExisting = App::call([new CartService, 'checkUserCartExisting'], ['userId' => Auth::user()->id]);

            if ($userCartExisting === true) {
                $result = App::call([new CartService, 'updateUserCart'], [
                    'userId' => Auth::user()->id,
                    'cartItemData' => Arr::except($request->input(), ['_token'])
                ]);
            } else {
                $result = App::call([new CartService, 'createUserCart'], [
                    'userId' => Auth::user()->id,
                    'cartItemData' => Arr::except($request->input(), ['_token'])
                ]);
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $result,
            ];

            //這本就不是restful api，200方便一點
            return response()->json($returnMessage, Response::HTTP_OK);
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
