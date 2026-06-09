<?php

namespace App\Permissions\V1;

final class Abilities
{
    public const string VIEW_TASK = 'view:task';
    public const string CREATE_TASK = 'create:task';
    public const string UPDATE_TASK = 'update:task';
    public const string DELETE_TASK = 'delete:task';

    public const string VIEW_OWN_TASK = 'view:own:task';
    public const string UPDATE_OWN_TASK = 'update:own:task';
    public const string DELETE_OWN_TASK = 'delete:own:task';
    public const string COMPLETE_OWN_TASK = 'complete:own:task';

    public static function getAbilities(): array
    {
        // Normal user
        return [
            self::VIEW,
            self::UPDATE_OWN_TASK,
            self::DELETE_OWN_TASK,
            self::COMPLETE_OWN_TASK
        ];
    }
}
