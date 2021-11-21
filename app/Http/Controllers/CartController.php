<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\AddToCart;
use App\Services\CartService;
use App\Services\GoodsService;
use Exception;
use Illuminate\Support\Arr;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    public function test()
    {
        return User::with('permissions')->get();
    }

    public function addItemToUserCart(AddToCart $request)
    {
        try {
            $userCartExisting = App::call([new CartService, 'checkUserCartExisting'], ['userId' => Auth::user()->id]);

            if ($userCartExisting === true) {
                return 'update';
                //$result = $cartService->updateItemInUserCart(Arr::except($request->input(), ['_token']));
            } else {
                return 'create';
                //$result = $cartService->addItemToUserCart(Arr::except($request->input(), ['_token']));
            }

            // $returnMessage = [
            //     'result' => 'SUCCESS',
            //     'content' => $result,
            // ];

            // return response()->json($returnMessage, Response::HTTP_CREATED);
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
