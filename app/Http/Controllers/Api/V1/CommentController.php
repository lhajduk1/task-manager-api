<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\{Project, Task, Comment};
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends ApiController
{
    public function index(Project $project, Task $task): AnonymousResourceCollection
    {
        return CommentResource::collection($task->comments()->paginate(10));
    }

    public function store(Request $request, Project $project, Task $task): CommentResource
    {
        $this->isAble('store', Comment::class);

        $comment = $task->comments()->create($request->validated());

        return new CommentResource($comment);
    }

    public function show(Project $project, Task $task, Comment $comment): CommentResource
    {
        return new CommentResource($comment);
    }

    public function update(Request $request, Project $project, Task $task, Comment $comment): CommentResource
    {
        $this->isAble('update', $comment);

        $comment = $comment->update($request->validated());

        return new CommentResource($comment);
    }

    public function destroy(Project $project, Task $task, Comment $comment): JsonResponse
    {
        $this->isAble('delete', $comment);

        $comment->delete();

        return response()->json([
            'message' => 'Comment was successfully deleted!'
        ]);
    }
}
