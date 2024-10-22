<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;

Auth::routes();

//Route::group(['namespace' => 'Home'], function () {
//    Route::get('/', [IndexController::class, '__invoke'])->name('home.index'); // Update route definition
//});
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/about', [AboutController::class, 'index'])->name('about.index');

Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.index')->middleware(\App\Http\Middleware\AdminMiddleware::class);
Route::get('/admin-panel/create-product', [AdminController::class, 'createProduct'])->name('admin.create-product')->middleware(\App\Http\Middleware\AdminMiddleware::class);
Route::get('/admin-panel/products', [AdminController::class, 'products'])->name('admin.products')->middleware(\App\Http\Middleware\AdminMiddleware::class);
Route::get('/admin-panel/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit')->middleware(\App\Http\Middleware\AdminMiddleware::class);
Route::put('/admin-panel/products/{id}', [ProductController::class, 'update'])->name('product.update')->middleware(\App\Http\Middleware\AdminMiddleware::class);
Route::get('/admin-panel/categories/search', [CategoryController::class, 'search'])->name('categories.search')->middleware(\App\Http\Middleware\AdminMiddleware::class);

Route::resource('/admin-panel/categories', CategoryController::class)
    ->middleware(\App\Http\Middleware\AdminMiddleware::class)
    ->names([
        'index' => 'admin.categories',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::post('/product/create', [ProductController::class, 'create'])->name('product.create');
Route::get('/search', [ProductController::class, 'search'])->name('product.search');
Route::get('/product/{id}', [ProductController::class, 'view'])->name('product.view');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/order/create', [OrderController::class, 'createOrder'])->name('order.create');

Route::post('/product/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin-panel/support', [ChatController::class, 'supportIndex'])->name('admin.support.index');
    Route::get('/admin-panel/support/chat/{chatId}/messages', [ChatController::class, 'getChatMessages']);
    Route::get('/support', [ChatController::class, 'index'])->name('support.index');
    Route::post('/chats/{chatId}/messages', [MessageController::class, 'store']);
});



