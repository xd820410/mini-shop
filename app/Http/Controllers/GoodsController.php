<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoodsService;
use App\Http\Requests\CreateGoods;
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

            return response()->json($returnMessage, 200);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, 404);
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

            return response()->json($returnMessage, 200);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, 404);
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

            return response()->json($returnMessage, 200);
        } catch (Exception $e) {
            $errorMessage = [
                'result' => 'ERROR',
                'message' => $e->getMessage(),
            ];

            return response()->json($errorMessage, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
