<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\App;

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

/**
 * 加入購物車
 * 註解為各式失敗方案
 */
//方案1. switcher => 在switcher就要注入用不到的service，那method injection是白做==
//Route::post('/cart', 'CartController@addItemToCartSwitcher');

//方案2. 應該是closure之外Auth facade還不生效
// $addItemToCartMethod = 'addItemToSessionCart';
// if (Auth::check()) {
//     $addItemToCartMethod = 'addItemToUserCart';
// }
// Route::post('/cart', function () use ($addItemToCartMethod) {
//     return $addItemToCartMethod;
// });
//方案2之衍生測試，closure內可正常判斷登出登入
// Route::post('/cart', function () {
//     return Auth::check();
// });

//方案3. 失敗原因應該同方案2
//Route::post('/cart', (Auth::check() == true) ? 'CartController@addItemToUserCart' : 'CartController@addItemToSessionCart');

//最終方案
Route::post('/cart', function () {
    $addItemToCartMethod = 'addItemToSessionCart';
    if (Auth::check()) {
        $addItemToCartMethod = 'addItemToUserCart';
    }

    return App::call([new CartController, $addItemToCartMethod]);
});

Route::get('/cart', function () {
    $getCartMethod = 'getSessionCart';
    if (Auth::check()) {
        $getCartMethod = 'getUserCart';
    }

    return App::call([new CartController, $getCartMethod]);
});

Route::post('/delete_item_from_cart', function () {
    $deleteItemFromCartMethod = 'deleteItemFromSessionCart';
    if (Auth::check()) {
        $deleteItemFromCartMethod = 'deleteItemFromUserCart';
    }

    return App::call([new CartController, $deleteItemFromCartMethod]);
});