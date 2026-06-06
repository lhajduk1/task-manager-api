<?php

namespace App\Policies\v1;

use App\Models\{Task, User};

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

      public function complete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
