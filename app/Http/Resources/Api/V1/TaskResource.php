<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\V1\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => 'task',
                'id' => $this->id,
                'attributes' => [
                    'title' => $this->title,
                    'description' => $this->when(
                        $request->routeIs('projects.tasks.show'),
                        $this->description
                    ),
                    'status' => $this->status,
                    'dueDate' => $this->due_date,
                    'createdAt' => $this->created_at,
                    'updatedAt' => $this->updated_at,
                ],
                'relationships' => [
                    'project' => [
                        'data' => [
                            'type' => 'project',
                            'id' => $this->project_id
                        ],
                        'links' => [
                            'self' => route('projects.show', ['project' => $this->project_id])
                        ]
                    ]
                ],
                'links' => [
                    'self' => route('tasks.show', ['project' => $this->project_id, 'task' => $this->id])
                ],
            ],
            'included' => new ProjectResource($this->whenLoaded('project')),
        ];
    }
}
