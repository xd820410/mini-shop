<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoodsService;
use App\Http\Requests\CreateGoods;
use Illuminate\Http\Response;
use Exception;

class GoodsController extends Controller
{
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
    public function store(CreateGoods $request, GoodsService $goodsService)
    {
        try {
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
    public function update(Request $request, $id, GoodsService $goodsService)
    {
        try {
            $result = $goodsService->updateById($id, $request->input());
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

    public function showGoodsManager()
    {
        return view('manager.goods');
    }
}
