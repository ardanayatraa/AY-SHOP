<?php

namespace App\Mcp\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use McpHub\Laravel\Contracts\ResourcePolicy;
use McpHub\Laravel\Security\Actor;

class OrderPolicy implements ResourcePolicy
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
            $sellerId = $user->seller->id;

            return $query->whereHas('items', fn (Builder $q) => $q->where('seller_id', $sellerId));
        }

        return $query->where('user_id', $user->id);
    }
}

