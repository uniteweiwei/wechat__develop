<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//微信开发
Route::any('/wx', 'WxController@index');

//登陆 微信授权登陆
Route::get('/login','UserController@login');
Route::get('/center','UserController@center');
Route::get('/logout','UserController@logout');

//商城建设
Route::get('/','ShopController@index');
Route::get('/goods/{id}','ShopController@goods');
Route::get('/cart/{id}','ShopController@cart');
Route::get('/cart_all','ShopController@cart_all');
Route::post('/done','ShopController@done');
Route::post('/payok','ShopController@payok');
