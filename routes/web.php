<?php

Route::get('/p', 'ProductController@index');
Route::get('/b', 'BrandController@index');



//首页
Route::get('/', 'ReviewController@index')->name('home');
//分类页
Route::resource('cats', 'CatController')->only(['show']);
//品牌页+所有品牌页
Route::resource('brands', 'BrandController')->only(['index', 'show']);
//商品页
Route::resource('products', 'ProductController')->only('show');
//个人中心
Route::resource('users', 'UserController')->only('show');
Route::post('users/{user}/avatars', 'UserController@avatar');
Route::patch('users/{user}/name', 'UserController@name_update');
Route::patch('users/{user}/skin', 'UserController@skin_update');
//搜索
Route::get('search', 'SearchController@search')->name('search');
Route::get('instant/search', 'SearchController@instant_search');


//登录（登录===注册）退出
//Route::get('login', 'UserController@showLoginForm')->name('login');
Route::post('login', 'UserController@login');
Route::get('logout', 'UserController@logout')->name('logout');

//排行榜-取排行
Route::get('ranking/{cat}', 'ReviewController@ranking');

//游客简易点评
Route::post('products/{product}/reviews/visitor', 'ReviewController@store_visitor');
//点评全家桶
Route::resource('products/{product}/reviews', 'ReviewController')->except(['create','show','edit','destroy']);
//上传图片到云服务器
Route::post('reviews/imgs', 'ReviewController@img_store')->name('img.store');

//删除点评，与上述传图路由冲突，遂放下面
Route::delete('reviews/{review}','ReviewController@destroy');

//点评点赞点踩
Route::post('vote', 'ReviewController@vote');






