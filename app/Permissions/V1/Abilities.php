<?php

namespace App\Permissions\V1;

final class Abilities
{
    // Project
    public const string VIEW_PROJECT = 'view:project';
    public const string CREATE_PROJECT = 'create:project';
    public const string UPDATE_PROJECT = 'update:project';
    public const string DELETE_PROJECT = 'delete:project';
    public const string TOGGLE_COMPLETE_PROJECT = 'toggle:complete:project';

    public const string VIEW_OWN_PROJECT = 'view:own:project';
    public const string CREATE_OWN_PROJECT = 'create:own:project';
    public const string UPDATE_OWN_PROJECT = 'update:own:project';
    public const string DELETE_OWN_PROJECT = 'delete:own:project';
    public const string TOGGLE_COMPLETE_OWN_PROJECT = 'toggle:complete:own:project';

    // Task
    public const string VIEW_TASK = 'view:task';
    public const string CREATE_TASK = 'create:task';
    public const string UPDATE_TASK = 'update:task';
    public const string DELETE_TASK = 'delete:task';
    public const string TOGGLE_COMPLETE_TASK = 'toggle:complete:task';

    public const string VIEW_OWN_TASK = 'view:own:task';
    public const string CREATE_OWN_TASK = 'create:own:task';
    public const string UPDATE_OWN_TASK = 'update:own:task';
    public const string DELETE_OWN_TASK = 'delete:own:task';
    public const string TOGGLE_COMPLETE_OWN_TASK = 'toggle:complete:own:task';

    public static function getAbilities(): array
    {
        // Normal user
        return [
            // Project
            self::VIEW_OWN_PROJECT,
            self::CREATE_OWN_PROJECT,
            self::UPDATE_OWN_PROJECT,
            self::DELETE_OWN_PROJECT,
            self::TOGGLE_COMPLETE_OWN_PROJECT,

            // Task
            self::VIEW_OWN_TASK,
            self::CREATE_OWN_TASK,
            self::UPDATE_OWN_TASK,
            self::DELETE_OWN_TASK,
            self::TOGGLE_COMPLETE_OWN_TASK
        ];
    }
}
