<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductInput\ProductInputController;

Route::get('/inputs', function () {
    return view('input.dashboard');
})->middleware('auth');

Route::prefix('product-inputs')->middleware('auth')->group(function () {
    Route::get('/list', [ProductInputController::class, 'index'])->name('input.index');
});