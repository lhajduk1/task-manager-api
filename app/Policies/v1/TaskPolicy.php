<?php

namespace App\Policies\v1;

use App\Models\{Task, User};
use App\Permissions\V1\Abilities;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::VIEW_OWN_TASK)) {
            return $user->id === $task->user_id;
        }

        return false;
    }

    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CREATE_OWN_TASK) ||
            $user->tokenCan(Abilities::CREATE_TASK);
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::UPDATE_OWN_TASK)) {
            return $user->id === $task->user_id;
        }

        return false;
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::DELETE_OWN_TASK)) {
            return $user->id === $task->user_id;
        }

        return false;
    }

    public function complete(User $user, Task $task): bool
    {
        if ($user->tokenCan(Abilities::COMPLETE_OWN_TASK)) {
            return $user->id === $task->user_id;
        }

        return false;
    }
}
