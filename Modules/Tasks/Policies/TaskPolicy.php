<?php

namespace Modules\Tasks\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Tasks\Entities\Task;
use Modules\Users\Entities\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tasks.view.any') || $user->can('tasks.view.own');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->can('tasks.view.any')
            || ($user->can('tasks.view.own') && $task->assigned_to === $user->id);
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can('tasks.update.any')
            || ($user->can('tasks.update.own') && $task->assigned_to === $user->id);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('tasks.delete.any');
    }

    public function create(User $user): bool
    {
        return $user->can('tasks.create');
    }
}
