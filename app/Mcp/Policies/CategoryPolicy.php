<?php

namespace App\Mcp\Policies;

use Illuminate\Database\Eloquent\Builder;
use McpHub\Laravel\Contracts\ResourcePolicy;
use McpHub\Laravel\Security\Actor;

class CategoryPolicy implements ResourcePolicy
{
    public function allows(Actor $actor, string $resource, string $action): bool
    {
        return in_array($action, ['list', 'get', 'count'], true);
    }

    public function scope(Builder $query, Actor $actor): Builder
    {
        return $query;
    }
}

