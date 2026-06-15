<?php

namespace App\Http\Filters\V1;

class ProjectFilter extends QueryFilter
{
    protected array $allowedIncludes = [
        'user',
        'tasks'
    ];

    protected array $sortable = [
        'title',
        'created_at'
    ];
}
