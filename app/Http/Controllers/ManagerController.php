<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use Illuminate\Support\Facades\Redis;

class ManagerController extends Controller
{
    public function redisPractice()
    {
        Redis::set('name', 'Taylor2');
        Redis::set('name2', 'huhu');
        //$response =  Redis::get('name');
        //Redis::flushall();

        $allKeys = Redis::keys('*');
        $response = [];
        foreach ($allKeys as $key) {
            $response[$key] = Redis::get($key);
        }

        return $response;
    }

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

    public function checkAdministratorPermission()
    {
        try {
            $administratorFlag = false;

            if (Auth::check()) {
                $permissions = Auth::user()->permissions;
                foreach ($permissions as $permission) {
                    if ($permission['right'] == Permission::administrator) {
                        $administratorFlag = true;
                        break;
                    }
                }
            }

            $returnMessage = [
                'result' => 'SUCCESS',
                'content' => $administratorFlag,
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
