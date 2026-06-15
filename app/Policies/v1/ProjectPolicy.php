<?php

namespace App\Policies\V1;

use App\Models\{User, Project};
use App\Permissions\V1\Abilities;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->tokenCan(Abilities::VIEW_OWN_PROJECT)) {
            return true;
        }

        return false;
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->tokenCan(Abilities::VIEW_OWN_PROJECT)) {
            return $user->id === $project->user_id;
        }

        return false;
    }

    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CREATE_OWN_PROJECT) ||
            $user->tokenCan(Abilities::CREATE_PROJECT);
    }

    public function update(User $user, Project $project): bool
    {
        if ($user->tokenCan(Abilities::UPDATE_OWN_PROJECT)) {
            return $user->id === $project->user_id;
        }

        return false;
    }

    public function delete(User $user, Project $project): bool
    {
        if ($user->tokenCan(Abilities::DELETE_OWN_PROJECT)) {
            return $user->id === $project->user_id;
        }

        return false;
    }
}
