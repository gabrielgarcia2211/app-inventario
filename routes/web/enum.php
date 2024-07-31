<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnumOption\EnumOptionController;

Route::prefix('enum')->middleware('auth')->group(function () {
    Route::get('/option/{name}', [EnumOptionController::class, 'listChildrens'])->name('enum.option');
});