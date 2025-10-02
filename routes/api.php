<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductControllerApi;
use App\Http\Controllers\Api\CategoryControllerApi;
use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\UserControllerApi;

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
Route::get('/product/{category_id?}', [ProductControllerApi::class, 'product_list']);
//Route::get('/product/search',[ProductControllerApi::class,'product_search']);
Route::post('/product/search',[ProductControllerApi::class,'product_search']);
Route::get('/category', [CategoryControllerApi::class, 'category_list']);
Route::post('/login', [AuthControllerApi::class, 'login']);
