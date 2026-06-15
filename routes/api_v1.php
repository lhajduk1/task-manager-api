<?php

use App\Http\Controllers\Api\V1\{ProjectController, TaskController};
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function (): void {
    Route::apiResource('projects', ProjectController::class);

    Route::apiResource('projects/{project}/tasks', TaskController::class);
    Route::put('projects/{project}/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggleComplete');
});
