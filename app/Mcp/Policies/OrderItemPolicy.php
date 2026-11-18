<?php

namespace App\Mcp\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use McpHub\Laravel\Contracts\ResourcePolicy;
use McpHub\Laravel\Security\Actor;

class OrderItemPolicy implements ResourcePolicy
{
    public function allows(Actor $actor, string $resource, string $action): bool
    {
        return in_array($action, ['list', 'get', 'count'], true);
    }

    public function scope(Builder $query, Actor $actor): Builder
    {
        /** @var User $user */
        $user = $actor->user;

        if ($user->role === 'admin') {
            return $query;
        }

        if ($user->seller) {
            return $query->where('seller_id', $user->seller->id);
        }

        return $query->whereHas('order', fn (Builder $q) => $q->where('user_id', $user->id));
    }
}

