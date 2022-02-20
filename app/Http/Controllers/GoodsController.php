<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoodsService;
use App\Services\RemoteGoodsService;
use App\Services\ImageProccessingService;
use App\Http\Requests\CreateGoods;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Exception;

class GoodsController extends Controller
{
    public function remoteGet(RemoteGoodsService $remoteGoodsService)
    {
        try {
            $result = $remoteGoodsService->getAll();
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => $result,
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

    public function remoteUpdate(RemoteGoodsService $remoteGoodsService)
    {
        try {
            $token = $remoteGoodsService->getToken();
            $remoteGoodsService->UpdateById($token, 9, [
                'title' => '測試一下',
                'price' => 81000,
            ]);

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => 'OK',
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

    public function showGoodsList()
    {
        return view('goods_list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GoodsService $goodsService)
    {
        try {
            $result = $goodsService->getAll();
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => $result,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGoods $request, GoodsService $goodsService, ImageProccessingService $imageProccessingService)
    {
        try {
            if ($request->hasFile('image') && !empty($request->file('image'))) {
                //return $request->file('image');
                $imagePath = $imageProccessingService->squareAndSave($request->file('image'));
                $request->merge(['image_path' => $imagePath]);
            }

            //return $request->input();
            $result = $goodsService->create($request->input());
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => $result,
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, GoodsService $goodsService)
    {
        try {
            $result = $goodsService->getById($id);
            $returnMessage = [
                'result' => 'SUCCESS',
                'message' => $result,
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, GoodsService $goodsService, ImageProccessingService $imageProccessingService)
    {
        try {
            if ($request->hasFile('image') && !empty($request->file('image'))) {
                /**
                 * 可判斷可不判斷，反正才一條query
                 */
                // $goodsData = $goodsService->getById($id);
                // if (!empty($goodsData['image_path'])) {
                //     $imageProccessingService->deleteGoodsImageByGoodsId($id);
                // }
                $imageProccessingService->deleteGoodsImageByGoodsId($id);

                $imagePath = $imageProccessingService->squareAndSave($request->file('image'));
                $request->merge(['image_path' => $imagePath]);
            }

            //return $request->input();
            $result = $goodsService->updateById($id, Arr::except($request->input(), ['_method']));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, GoodsService $goodsService)
    {
        try {
            $result = $goodsService->deleteById($id);
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
}