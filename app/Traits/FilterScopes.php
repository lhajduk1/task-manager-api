<?php

namespace App\Traits;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait FilterScopes
{
    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }
}
