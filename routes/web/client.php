<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;

Route::prefix('clients')->middleware('auth')->group(function () {
    Route::get('/list/name', [ClientController::class, 'getEnumClientName'])->name('clients.name');
});