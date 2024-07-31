<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductSize\ProductSizeController;

Route::prefix('product-size')->middleware('auth')->group(function () {
    Route::post('/enter/{id}', [ProductSizeController::class, 'enterProduct'])->name('products.enter');
    Route::post('/extract/{id}', [ProductSizeController::class, 'extractProduct'])->name('products.extract');
});