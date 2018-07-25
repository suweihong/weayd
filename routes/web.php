<?php

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

Route::get('/','LoginController@index');//首页
Route::get('type/stores','StoresController@type_stores');//某个运动品类 相关的 商家
Route::get('store/show','StoresController@store_show');//商家详情
Route::resource('orders','OrdersController');//订单
Route::resource('fields','FieldsController');//场地
Route::post('estimates/store','EstimatesController@estimates');//添加评论
Route::get('estimates/list','EstimatesController@list');//评论列表
