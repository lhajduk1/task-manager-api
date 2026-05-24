<?php

use App\Http\Controllers\Auth\{RegisterController, LoginController, LogoutController};
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'throttle:auth'], function () {
    Route::post('/register', RegisterController::class)->name('auth.register');
    Route::post('/login', LoginController::class)->name('auth.login');
});

Route::group(['middleware' => 'auth:sanctum'], function (): void {
    Route::post('/logout', LogoutController::class)->name('auth.logout');
});
