<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Builder $builder;
    protected array $allowedIncludes;
    protected array $sortable;

    public function __construct(private Request $request) {}

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->{$key}($value);
            }
        }

        return $builder;
    }

    public function include(string $value): Builder
    {
        $includes = collect(explode(',', $value))
            ->filter()
            ->intersect($this->allowedIncludes)
            ->values()
            ->all();

        return $this->builder->with($includes);
    }

    public function filter(array $arr): Builder
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->{$key}($value);
            }
        }

        return $this->builder;
    }

    public function sort(string $value): void
    {
        $sortables = explode(',', $value);

        foreach ($sortables as $sortable) {
            $direction = Str::startsWith($sortable, '-') ? 'desc' : 'asc';
            $column = Str::of($sortable)->remove('-')->snake()->value();

            if (in_array($column, $this->sortable)) {
                $this->builder->orderBy($column, $direction);
            }
        }
    }
}
