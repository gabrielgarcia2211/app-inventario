<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductOutflow\ProductOutflowController;

Route::get('/outflows', function () {
    return view('outflow.dashboard');
})->middleware('auth');

Route::prefix('outflows')->middleware('auth')->group(function () {
    Route::get('/list', [ProductOutflowController::class, 'index'])->name('outflows.index');
});