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

Route::post('customer/show', 'GnmstcustomerController@show');
Route::post('customer/add', 'GnmstcustomerController@add');
Route::put('customer/update/{gnmstcustomer}', 'GnmstcustomerController@update');

Route::post('supplier/show', 'GnmstsupplierController@show');
Route::post('supplier/add', 'GnmstsupplierController@add');
Route::post('supplier/update', 'GnmstsupplierController@update');

Route::post('item/show', 'SpmstitemController@show');
Route::post('item/showtwo', 'SpmstitemController@showtwo');
Route::post('item/add', 'SpmstitemController@add');

Route::post('transrcvhdr/show', 'SptrnprcvhdrController@show');
Route::post('transrcvhdr/add', 'SptrnprcvhdrController@add');
Route::post('transrcvhdr/detail', 'SptrnprcvhdrController@addDetail');

Route::post('transinvoice/add', 'SptrnsinvoiceController@add');

Route::post('srvservice/add', 'SvtrnsinvoiceController@add');
