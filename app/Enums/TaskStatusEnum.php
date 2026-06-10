<?php

namespace App\Enums;


enum TaskStatusEnum: string
{
    case TODO = 'todo';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::TODO => 'Todo',
            self::DONE => 'Done',
        };
    }
}
