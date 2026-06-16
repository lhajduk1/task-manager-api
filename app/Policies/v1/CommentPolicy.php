<?php

namespace App\Policies\V1;

use App\Models\Comment;
use App\Models\User;
use App\Permissions\V1\Abilities;

class CommentPolicy
{
    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CREATE_OWN_COMMENT);
    }

    public function update(User $user, Comment $comment): bool
    {
        if ($user->tokenCan(Abilities::UPDATE_OWN_COMMENT)) {
            return $user->id === $comment->user_id;
        }

        return false;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->tokenCan(Abilities::DELETE_OWN_COMMENT)) {
            return $user->id === $comment->user_id;
        }

        return false;
    }
}
