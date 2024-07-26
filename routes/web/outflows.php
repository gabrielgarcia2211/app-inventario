<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductOutflow\ProductOutflowController;

Route::prefix('outflows')->middleware('auth')->group(function () {
    Route::get('/', [ProductOutflowController::class, 'index'])->name('outflows.index');
});