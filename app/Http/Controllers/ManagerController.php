<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class ManagerController extends Controller
{
    public function showGoodsManager()
    {
        return view('manager.goods');
    }

    public function getToken(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            $token = $request->user()->createToken('editor', ['edit'])->plainTextToken;
            $returnMessage = [
                'result' => 'SUCCESS',
                'token' => $token,
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
}
