<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\V1\{StoreTaskRequest, UpdateTaskRequest};
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskFilter $filter): AnonymousResourceCollection
    {
        return TaskResource::collection(Task::filter($filter)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $user = Auth::user();

        return new TaskResource($user->tasks()->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Task was successfully deleted!'
        ]);
    }

    /**
     * Mark status as completed for the specified resource in storage.
     */
    public function complete(Task $task): TaskResource
    {
        $task->update([
            'status' => TaskStatusEnum::DONE
        ]);

        return new TaskResource($task);
    }
}
