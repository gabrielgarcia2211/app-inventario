<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Notification\NotificationController;

Route::prefix('notifications')->middleware('auth')->group(function () {
    Route::get('/list', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/list/noread', [NotificationController::class, 'listUnread'])->name('notifications.list.noread');
    Route::post('/update/status/{id}', [NotificationController::class, 'updateStatus'])->name('notifications.update.status');
});