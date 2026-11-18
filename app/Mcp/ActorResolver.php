<?php

namespace App\Mcp;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use McpHub\Laravel\Contracts\ActorResolver as ActorResolverContract;
use McpHub\Laravel\Security\Actor;

class ActorResolver implements ActorResolverContract
{
    public function forUser(Authenticatable $user): Actor
    {
        if (! $user instanceof User) {
            throw new AuthorizationException('Unsupported user model.');
        }

        $reference = 'user:' . $user->getAuthIdentifier();
        $tenant = $this->resolveTenantForUser($user);

        return new Actor($user, $reference, $tenant);
    }

    public function resolve(string $userReference, string $tenant): Actor
    {
        $userId = str_starts_with($userReference, 'user:')
            ? substr($userReference, 5)
            : $userReference;

        $user = User::with('seller')->find($userId);

        if (! $user) {
            throw new AuthorizationException('User account not found.');
        }

        $expectedTenant = $this->resolveTenantForUser($user);
        if ($tenant !== $expectedTenant) {
            throw new AuthorizationException('Actor tenant mismatch.');
        }

        return new Actor($user, 'user:' . $user->id, $expectedTenant);
    }

    private function resolveTenantForUser(User $user): string
    {
        if ($user->role === 'admin') {
            return 'admin';
        }

        if ($user->seller) {
            return 'seller:' . $user->seller->id;
        }

        return 'customer:' . $user->id;
    }
}

