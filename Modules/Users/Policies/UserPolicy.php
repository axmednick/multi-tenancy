<?php

namespace Modules\Users\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Users\Entities\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser): bool
    {
        return $authUser->can('users.manage');
    }

    public function view(User $authUser, User $user): bool
    {
        return $authUser->can('users.manage')
            || $authUser->id === $user->id;
    }

    public function create(User $authUser): bool
    {
        return $authUser->can('users.manage');
    }

    public function update(User $authUser, User $user): bool
    {
        return $authUser->can('users.manage')
            || $authUser->id === $user->id;
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->can('users.manage');
    }
}
