<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatusEnum;
use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\V1\{StoreTaskRequest, UpdateTaskRequest};
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\Task;
use App\Policies\v1\TaskPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends ApiController
{
    use AuthorizesRequests;

    protected string $policyClass = TaskPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TaskFilter $filter): AnonymousResourceCollection
    {
        return TaskResource::collection(
            $request->user()->tasks()->filter($filter)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $this->isAble('store', Task::class);

        return new TaskResource(
            $request->user()->tasks()->create($request->validated())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        $this->isAble('view', $task);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $this->isAble('update', $task);

        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->isAble('delete', $task);

        $task->delete();

        return response()->json([
            'message' => 'Task was successfully deleted!'
        ]);
    }

    /**
     * Mark status as completed for the specified resource in storage.
     */
    public function complete(Task $task): TaskResource|JsonResponse
    {
        $this->isAble('complete', $task);

        $task->update(['status' => TaskStatusEnum::DONE]);

        return new TaskResource($task);
    }
}
