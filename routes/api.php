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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/','ReviewController@api_index');
//Route::get('recent','ReviewController@recent');

Route::post('login','UserController@api_login');
Route::get('users/{openid}','UserController@api_show');
Route::post('users/{openid}/avatars', 'UserController@api_avatar');
Route::put('users/{openid}/name', 'UserController@api_name_update');
Route::put('users/{openid}/skin', 'UserController@api_skin_update');
Route::get('other_users/{user_id}','UserController@api_other_show');

Route::get('search', 'SearchController@instant_search');

Route::get('brands/{brand_id}','BrandController@api_show');
Route::get('brands','BrandController@api_index');

Route::get('cats/{cat_id}','CatController@api_show');
Route::get('user_cats','CatController@user_index');
Route::get('cats','CatController@api_index');

Route::get('products/{product_id}','ProductController@api_show');
Route::get('products/{product_id}/review','ProductController@api_my_review');

Route::post('reviews/imgs', 'ReviewController@api_img_store');
Route::post('products/{product}/reviews','ReviewController@api_store');
Route::put('reviews/{review}','ReviewController@api_update');
Route::delete('reviews/{review}','ReviewController@api_destroy');
Route::post('vote', 'ReviewController@vote');


//Route::get('test',function(){
//    return ['a'=>11];
//});



