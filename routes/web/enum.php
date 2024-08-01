<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnumOption\EnumOptionController;

Route::get('/enums', function () {
    return view('admin.enum');
})->middleware('auth');

Route::prefix('enums')->middleware('auth')->group(function () {
    Route::get('/list', [EnumOptionController::class, 'index'])->name('enum.index');
    Route::post('/store', [EnumOptionController::class, 'store'])->name('enum.store');
    Route::post('/update/{id}', [EnumOptionController::class, 'update'])->name('enum.update');
    Route::get('/fathers', [EnumOptionController::class, 'listFathers'])->name('enum.fathers');
    Route::get('/option/{name}', [EnumOptionController::class, 'listChildrens'])->name('enum.option');
    Route::delete('/{id}', [EnumOptionController::class, 'destroy'])->name('enum.destroy');
});