<?php
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
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
Route::post('/cart/finish', [CartController::class, 'finish_order'])->name('cart.finish');

// Order routes

Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order/{id}/status', [OrderController::class, 'update'])->name('order.update');
Route::post('/order/search', [OrderController::class, 'search']);



Route::middleware(['auth'])->group(function () {
    
    // Home route - จะ redirect ตาม user level
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // === Routes สำหรับ Customer (ทุก level เข้าได้) ===
    Route::get('/catalog', [ProductController::class, 'catalog'])->name('product.catalog');
    
    // === Routes สำหรับ Admin และ Employee ===
    Route::middleware(['check.level:admin,employee'])->group(function () {
        
        // Product Management (CRUD)
        Route::resource('product', ProductController::class);
        
        // Category Management (CRUD)
        Route::resource('category', CategoryController::class);
        
        // Branch Management (CRUD) - ถ้ามี
        Route::resource('branch', BranchController::class);
    });
    
    // === Routes สำหรับ Employee (รวม Admin ด้วย) - จัดการ Orders ===
    Route::middleware(['check.level:employee,admin'])->group(function () {
        
        // Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::post('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
            Route::patch('/{id}/status-ajax', [OrderController::class, 'updateStatusAjax'])->name('updateStatusAjax');
        });
    });
    
    // === Routes สำหรับ Admin เท่านั้น ===
    Route::middleware(['check.level:admin'])->group(function () {
        
        // User Management (CRUD)
        Route::resource('users', UserController::class);
        
        // Additional admin routes if needed
        Route::get('/dashboard/admin', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
    
    // === Routes สำหรับ Customer เท่านั้น ===
    Route::middleware(['check.level:customer'])->group(function () {
        
        // Customer specific routes
        Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my.orders');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    });
});

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});