<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
                'type' => 'comment',
                'id' => $this->id,
                'attributes' => [
                    'body' => $this->body,
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
                    'task' => [
                        'data' => [
                            'type' => 'task',
                            'id' => $this->task_id
                        ]
                    ]
                ],
                'links' => [
                    // TODO: Implement show method.
                ]
            ],
            'included' => [
                new UserResource($this->whenLoaded('user')),
                new TaskResource($this->whenLoaded('task'))
            ]
        ];
    }
}
