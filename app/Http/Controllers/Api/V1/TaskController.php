<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\v1\ToggleCompleteTaskAction;
use App\Http\Filters\V1\TaskFilter;
use App\Http\Requests\V1\{StoreTaskRequest, UpdateTaskRequest};
use App\Http\Resources\Api\V1\TaskResource;
use App\Models\{Task, Project};
use App\Policies\V1\TaskPolicy;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends ApiController
{
    protected string $policyClass = TaskPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project, TaskFilter $filter): AnonymousResourceCollection
    {
        $this->isAble('viewAny', [Task::class, $project]);

        return TaskResource::collection(
            $project->tasks()->filter($filter)->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, Project $project): TaskResource
    {
        $this->isAble('store', [Task::class, $project]);

        return new TaskResource(
            $project->tasks()->create($request->validated())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task): TaskResource
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
    public function toggleComplete(Task $task, ToggleCompleteTaskAction $action): TaskResource
    {
        $this->isAble('toggleComplete', $task);

        return new TaskResource(
            $action->execute($task)
        );
    }
}
