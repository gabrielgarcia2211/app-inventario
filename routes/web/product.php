<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;

Route::get('/', function () {
    return view('product.dashboard');
})->middleware('auth');

Route::prefix('products')->middleware('auth')->group(function () {
    Route::get('/list', [ProductController::class, 'index'])->name('products.index');
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/list/size', [ProductController::class, 'getEnumProductSize'])->name('products.size');
    Route::get('/list/section', [ProductController::class, 'getEnumProductSection'])->name('products.section');
});