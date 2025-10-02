<?php
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
<<<<<<< HEAD
use App\Http\Controllers\OrderController;
=======
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
>>>>>>> feature_order
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;


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


// Cart routes
Route::get('/cart/checkout', [CartController::class, 'checkout']);
Route::get('/cart/complete', [CartController::class, 'complete']);
<<<<<<< HEAD


Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::post('/users/search', [App\Http\Controllers\UserController::class, 'search']);
Route::get('/users/edit/{id?}', [App\Http\Controllers\UserController::class, 'edit']);
Route::post('/users/update', [App\Http\Controllers\UserController::class, 'update']);
Route::get('/users/remove/{id}', [App\Http\Controllers\UserController::class, 'remove']);
Route::post('/users/add', [App\Http\Controllers\UserController::class, 'insert']);

Route::post('/orders/finish', [OrderController::class, 'finish_order'])->name('orders.finish');

Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{order}', [OrderController::class, 'edit'])->name('orders.edit');

Route::post('/orders/{order}', [OrderController::class, 'updateStatus'])->name('orders.edit');
=======
Route::post('/cart/finish', [CartController::class, 'finish_order'])->name('cart.finish');



Route::get('/order', [OrderController::class, 'index'])->name('order.index');

Route::middleware(['auth'])->group(function () {

    // Home route - จะ redirect ตาม user level
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ถ้าเป็น employee หรือ admin ให้ไปที่หน้า /product
    Route::middleware(['check.level:employee,admin'])->group(function () {
        Route::get('/redirect-product', function () {
            return redirect('/product');
        })->name('redirect.product');

        Route::resource('product', ProductController::class);
        Route::resource('category', CategoryController::class);
    });

    // === Routes สำหรับ Admin เท่านั้น
    Route::middleware(['check.level:admin'])->group(function () {


        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/search', [UserController::class, 'search'])->name('users.search');

        Route::get('/users/add', [UserController::class, 'edit'])->name('users.add');
        Route::post('/users/add', [UserController::class, 'insert'])->name('users.insert');

        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/update', [UserController::class, 'update'])->name('users.update');


        Route::get('/users/remove/{id}', [UserController::class, 'remove'])->name('users.remove');

    });
        
    // === Routes สำหรับ Customer เท่านั้น ===
    Route::middleware(['check.level:employee'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/order', [OrderController::class, 'index'])->name('order.index');
        Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
        Route::post('/order/{id}/status', [OrderController::class, 'update'])->name('order.update');
        Route::post('/order/search', [OrderController::class, 'search']);
        
    });

    Route::middleware(['check.level:customer'])->group(function () {

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    });
});
>>>>>>> feature_order

