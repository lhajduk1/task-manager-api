<?php

namespace App\Actions\v1;

use App\Models\Task;

class ToggleCompleteTaskAction
{
    public function execute(Task $task)
    {
        $task->update(['status' => $task->status->toggle()]);

        return $task;
    }
}
