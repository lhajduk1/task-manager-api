<?php

use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function (): void {
    Route::apiResource('tasks', TaskController::class);
    Route::put('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
});
