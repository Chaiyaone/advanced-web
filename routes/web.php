<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/product', [App\Http\Controllers\ProductController::class, 'index']);
Route::post('/product/search', [App\Http\Controllers\ProductController::class, 'search']);
Route::get('/product/edit/{id?}', [App\Http\Controllers\ProductController::class, 'edit']);
Route::post('/product/update', [App\Http\Controllers\ProductController::class, 'update']);
Route::post('/product/add', [App\Http\Controllers\ProductController::class, 'insert']);
Route::get('/product/remove/{id}', [App\Http\Controllers\ProductController::class, 'remove']);

Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index']);
Route::post('/category/search', [App\Http\Controllers\CategoryController::class, 'search']);
Route::get('/category/edit/{id?}', [App\Http\Controllers\CategoryController::class, 'edit']);
Route::post('/category/update', [App\Http\Controllers\CategoryController::class, 'update']);
Route::post('/category/add', [App\Http\Controllers\CategoryController::class, 'insert']);
Route::get('/category/remove/{id}', [App\Http\Controllers\CategoryController::class, 'remove']);

Route::get('/cart/view', [CartController::class, 'viewCart']);
Route::get('/cart/add/{id}', [CartController::class, 'addToCart']);
Route::get('/cart/delete/{id}', [CartController::class, 'deleteCart']);
Route::get('/cart/update/{id}/{qty}', [CartController::class, 'updateCart']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout']);

Route::get('/cart/checkout', [CartController::class, 'checkout']);
Route::get('/cart/complete', [CartController::class, 'complete']);
Route::post('/cart/finish', [CartController::class, 'finish_order'])->name('cart.finish');

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{order}', [OrderController::class, 'edit'])->name('orders.edit');
