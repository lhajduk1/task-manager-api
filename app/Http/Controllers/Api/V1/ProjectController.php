<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ProjectFilter;
use App\Http\Requests\V1\{StoreProjectRequest, UpdateProjectRequest};
use App\Http\Resources\Api\V1\ProjectResource;
use App\Models\Project;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ProjectFilter $filter): AnonymousResourceCollection
    {
        $this->isAble('viewAny', Project::class);

        return ProjectResource::collection(
            $request->user()->projects()->filter($filter)->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): ProjectResource
    {
        $this->isAble('store', Project::class);

        return new ProjectResource(
            $request->user()->projects()->create(
                $request->validated()
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): ProjectResource
    {
        $this->isAble('view', $project);

        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        $this->isAble('update', $project);

        $project->update($request->validated());

        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->isAble('delete', $project);

        $project->delete();

        return response()->json([
            'message' => 'Project was successfully deleted!'
        ]);
    }
}
