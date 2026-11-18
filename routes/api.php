<?php

use App\Http\Controllers\Api\AccountApiController;
use Illuminate\Support\Facades\Route;

// Read-only API for signed-in users (the MCP Hub embedded chat signs in here).
Route::post('/login', [AccountApiController::class, 'login'])->middleware('throttle:20,1');

Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
    Route::get('/me', [AccountApiController::class, 'me']);
    Route::get('/my/orders', [AccountApiController::class, 'myOrders']);
    Route::get('/seller/products', [AccountApiController::class, 'sellerProducts']);
    Route::get('/seller/orders', [AccountApiController::class, 'sellerOrders']);
    Route::get('/products/search', [AccountApiController::class, 'searchProducts']);
});
