<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Storefront
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'shop'])->name('shop.catalog');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');
Route::get('/sellers', [ShopController::class, 'sellers'])->name('shop.sellers');
Route::get('/toko/{slug}', [ShopController::class, 'seller'])->name('shop.seller');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/success/{orderNumber}', [CartController::class, 'success'])->name('checkout.success');

Route::middleware('auth')->group(function () {
    Route::get('/pesanan-saya', [CartController::class, 'myOrders'])->name('orders.my');

    // Buka Toko (untuk user yang belum menjadi seller)
    Route::get('/buka-toko', [DashboardController::class, 'showRegister'])->name('seller.register');
    Route::post('/buka-toko', [DashboardController::class, 'registerStore'])->name('seller.register.store');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Menu Seller (Seller Center)
Route::prefix('seller-center')
    ->name('seller.')
    ->middleware(['auth', 'seller'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', SellerProductController::class)->except(['show']);
        Route::patch('products/{product}/toggle', [SellerProductController::class, 'toggleStatus'])->name('products.toggle');

        Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
        Route::patch('/orders/{orderItem}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.status');

        Route::get('/store', [DashboardController::class, 'editStore'])->name('store');
        Route::put('/store', [DashboardController::class, 'updateStore'])->name('store.update');
    });
