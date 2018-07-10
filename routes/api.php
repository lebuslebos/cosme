<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/','ReviewController@api_index');
//Route::get('wx_login','UserController@api_wx_login');
//Route::get('pre_login','UserController@api_pre_login');
Route::post('login','UserController@api_login');
Route::get('users/{openid}','UserController@api_show');
Route::post('users/{openid}/avatars', 'UserController@api_avatar');
Route::put('users/{openid}/name', 'UserController@api_name_update');
Route::put('users/{openid}/skin', 'UserController@api_skin_update');

Route::get('search', 'SearchController@instant_search');

Route::get('products/{product_id}','ProductController@api_show');

Route::get('brands/{brand_id}','BrandController@api_show');

Route::get('cats/{cat_id}','CatController@api_show');
Route::get('cats','CatController@api_index');


//Route::get('test',function(){
//    return ['a'=>11];
//});



