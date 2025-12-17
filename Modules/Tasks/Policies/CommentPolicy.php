<?php

namespace Modules\Tasks\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Tasks\Entities\Comment;
use Modules\Users\Entities\User;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->hasRole('admin');
    }
}
