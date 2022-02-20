<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class RemoteGoodsService
{
    public function getAll()
    {
        $response = Http::get('http://localhost/api/goods');
        $response->throw();

        return $response['message'];
    }

    public function getToken()
    {
        $login = Http::asForm()->post('http://localhost/login', [
            'email' => 'xd8204102@gmail.com',
            'password' => 'test1234',
            '_token' => csrf_token()
        ]);
        $login->throw();

        //return $login->cookies();
        $sessionCookie = $login
        ->cookies()
        ->getCookieByName('huhus_shop_session')
        ->toArray();
        $sessionCookieName = $sessionCookie['Name'];
        $sessionCookieValue = $sessionCookie['Value'];

        $response = Http::withHeaders([
            'Cookie' => $sessionCookieName . '=' . $sessionCookieValue,
        ])->get('http://localhost/manager/get_token');
        $response->throw();

        return $response['token'];
    }

    public function UpdateById($token, $goodsId, $data)
    {
        $response = Http::withToken($token)->patch('http://localhost/api/goods/' . $goodsId, $data);
        $response->throw();

        return $response->body();
    }
}
