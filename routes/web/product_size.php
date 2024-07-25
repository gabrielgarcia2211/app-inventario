<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductSize\ProductSizeController;

Route::prefix('product-size')->middleware('auth')->group(function () {
    Route::post('/extract', [ProductSizeController::class, 'extractProduct'])->name('products.extract');
});