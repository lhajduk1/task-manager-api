<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TaskFilter extends QueryFilter
{
    protected array $allowedIncludes = [
        'project'
    ];

    protected array $sortable = [
        'title',
        'status',
        'due_date',
        'created_at'
    ];

    public function status(string $value): Builder
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function dueDate(string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('due_date', $this->normalizeDueDateRange($dates));
        }

        return $this->builder->whereDate('due_date', $value);
    }

    private function normalizeDueDateRange(array $dateRange): array
    {
        return [
            Carbon::createFromFormat('Y-m-d', $dateRange[0])->startOfDay(),
            Carbon::createFromFormat('Y-m-d', $dateRange[1])->endOfDay(),
        ];
    }
}
