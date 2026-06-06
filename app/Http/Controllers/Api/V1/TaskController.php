<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatusEnum;
use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\V1\{StoreTaskRequest, UpdateTaskRequest};
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use App\Policies\v1\TaskPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class TaskController extends ApiController
{
    use AuthorizesRequests;

    protected string $policyClass = TaskPolicy::class;
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
    public function update(UpdateTaskRequest $request, Task $task): TaskResource|JsonResponse
    {
        try {
            $this->isAble('update', $task);

            $task->update($request->validated());

            return new TaskResource($task);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task cannot be found.'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to update that resource.'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->isAble('delete', $task);

            $task->delete();

            return response()->json([
                'message' => 'Task was successfully deleted!'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task cannot be found.'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to delete that resource.'
            ], 401);
        }
    }

    /**
     * Mark status as completed for the specified resource in storage.
     */
    public function complete(Task $task): TaskResource|JsonResponse
    {
        try {
            $this->isAble('complete', $task);

            $task->update(['status' => TaskStatusEnum::DONE]);

            return new TaskResource($task);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task cannot be found.'
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'You are not authorized to mark as completed that resource.'
            ], 401);
        }
    }
}
