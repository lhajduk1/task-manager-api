<?php

use App\Http\Controllers\Api\V1\{CommentController, ProjectController, TaskController};
use Illuminate\Support\Facades\Route;

Route::scopeBindings()
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::apiResource('projects', ProjectController::class);

        Route::apiResource('projects.tasks', TaskController::class);
        Route::patch('projects/{project}/tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggleComplete');

        Route::apiResource('projects.tasks.comments', CommentController::class);

        // Shallow method
        // Route::apiResource('projects.tasks.comments', CommentController::class)->shallow();

        // GET    /projects/{project}/tasks/{task}/comments
        // POST   /projects/{project}/tasks/{task}/comments

        // GET    /comments/{comment}
        // PUT    /comments/{comment}
        // PATCH  /comments/{comment}
        // DELETE /comments/{comment}
    });
