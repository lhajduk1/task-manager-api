<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection as SupportCollection;

class ProjectResource extends JsonResource
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
                'type' => 'project',
                'id' => $this->id,
                'attributes' => [
                    'title' => $this->title,
                    'description' => $this->description,
                    'createdAt' => $this->created_at,
                    'updatedAt' => $this->updated_at,
                ],
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'user',
                            'id' => $this->user_id
                        ]
                    ],
                    'tasks' => [
                        'links' => [
                            'related' => route('projects.tasks.index', ['project' => $this->id])
                        ],
                        'data' => $this->whenLoaded(
                            'tasks',
                            fn() => $this->tasks->map(fn($task) => [
                                'type' => 'task',
                                'id' => (string) $task->id,
                            ])
                        ),
                    ]
                ],
                'links' => [
                    'self' => route('projects.show', ['project' => $this->id])
                ]
            ],
            'included' => [
                new UserResource($this->whenLoaded('user')),
                TaskResource::collection($this->whenLoaded('tasks'))
            ],
        ];
    }
}
