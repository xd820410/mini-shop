<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('goods_list');
});

Route::get('/home', 'HomeController@index')->name('home');

//管理員可看
Route::group(['prefix' => 'manager', 'middleware' => ['manager', 'merge_session_cart']], function () {
    Route::get('/get_token', 'ManagerController@getToken');
    Route::get('/goods', 'ManagerController@showGoodsManager');
});

Route::post('/cart', 'CartController@addItemToCart');

Route::get('/orm-test', 'CartController@test');
