<?php

namespace App\Policies\V1;

use App\Models\{Task, User, Project};
use App\Permissions\V1\Abilities;

class TaskPolicy
{
    public function viewAny(User $user, Project $project): bool
    {
        if ($user->tokenCan(Abilities::VIEW_OWN_TASK)) {
            return $user->id === $project->user_id;
        }

        return false;
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::VIEW_OWN_TASK)) {
            return $user->id === $task->project->user_id;
        }

        return false;
    }

    public function store(User $user, Project $project): bool
    {
        if ($user->tokenCan(Abilities::CREATE_OWN_PROJECT)) {
            return $user->id === $project->user_id;
        }

        return false;
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::UPDATE_OWN_TASK)) {
            return $user->id === $task->project->user_id;
        }

        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::DELETE_OWN_TASK)) {
            return $user->id === $task->project->user_id;
        }

        return false;
    }

    public function toggleComplete(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::TOGGLE_COMPLETE_OWN_TASK)) {
            return $user->id === $task->project->user_id;
        }

        return false;
    }
}
