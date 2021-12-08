<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AddToCart;
use App\Http\Requests\EditItemQuantityFromCart;
use App\Services\CartService;
use App\Services\DiscountService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class CartController extends Controller
{
    public function editItemQuantityFromUserCart(AddToCart $request)
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

            return response()->json($returnMessage, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function editItemQuantityFromSessionCart(EditItemQuantityFromCart $request, CartService $cartService)
    {
        try {
            $checkItemInCart = $cartService->checkItemInSessionCart($request->input('goods_id'));

            if ($checkItemInCart === true) {
                $result = $cartService->updateItemInSessionCart(Arr::except($request->input(), ['_token']));
            } else {
                $result = [];
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $result,
            ];

            return response()->json($returnMessage, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteItemFromUserCart(Request $request)
    {
        try {
            $result = App::call([new CartService, 'deleteItemFromUserCart'], ['userId' => Auth::user()->id, 'goodsId' => $request->input('goods_id')]);
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => $result,
            ];

            return response()->json($returnMessage, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function deleteItemFromSessionCart(Request $request, CartService $cartService)
    {
        try {
            $result = $cartService->deleteItemFromSessionCart($request->input()['goods_id']);
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => $result,
            ];

            return response()->json($returnMessage, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, Response::HTTP_NOT_FOUND);
        }
    }

    public function getUserCart(CartService $cartService, DiscountService $discountService)
    {
        try {
            $cart = App::call([new CartService, 'getUserCart'], ['userId' => Auth::user()->id]);
            if (!empty($cart)) {
                $cart = App::call([new CartService, 'fillItemDataInCart'], ['cart' => $cart]);

                $now = Carbon::now();
                $nowString = $now->toDateTimeString();
                $effectiveDiscount = $discountService->getByDate($nowString);

                if (!empty($effectiveDiscount)) {
                    $cart = $cartService->calculateDiscount($cart, $effectiveDiscount);
                }
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

    public function getSessionCart(CartService $cartService, DiscountService $discountService)
    {
        try {
            $cart = $cartService->getSessionCart();
            if (!empty($cart)) {
                $cart = App::call([new CartService, 'fillItemDataInCart'], ['cart' => $cart]);

                $now = Carbon::now();
                $nowString = $now->toDateTimeString();
                $effectiveDiscount = $discountService->getByDate($nowString);

                if (!empty($effectiveDiscount)) {
                    $cart = $cartService->calculateDiscount($cart, $effectiveDiscount);
                }
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $cart,
                //'total' => $total,
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
                $cart = App::call([new CartService, 'getUserCart'], ['userId' => Auth::user()->id]);
                //簡易加法沒想到怎麼命名，姑且先寫在這了
                $quantityNow = 0;
                if (isset($cart['goods_' . $request->input('goods_id')]) && !empty($cart['goods_' . $request->input('goods_id')])) {
                    $quantityNow = (int) $cart['goods_' . $request->input('goods_id')]['quantity'];
                }
                $data = [];
                $data['goods_id'] = $request->input('goods_id');
                $data['quantity'] = $request->input('quantity') + $quantityNow;

                $result = App::call([new CartService, 'updateUserCart'], [
                    'userId' => Auth::user()->id,
                    'cartItemData' => $data
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
                $cart = $cartService->getSessionCart();
                //簡易加法沒想到怎麼命名，姑且先寫在這了
                $quantityNow = $cart['goods_' . $request->input('goods_id')]['quantity'];
                $data = [];
                $data['goods_id'] = $request->input('goods_id');
                $data['quantity'] = $request->input('quantity') + $quantityNow;
                $result = $cartService->updateItemInSessionCart($data);
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
