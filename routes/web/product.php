<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;

// Definir las rutas del controlador ProductController
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/list/size', [ProductController::class, 'getProductSize'])->name('products.size');
});