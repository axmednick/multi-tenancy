<?php

namespace Modules\Users\Repositories;

use Modules\Users\Entities\User;

class UserRepository
{
    public function getPaginatedWithRoles(int $perPage = 12)
    {
        return User::with('roles')->paginate($perPage);
    }
    public function getAllWithRoles($perPage = 12)
    {
        return User::with('roles')->paginate($perPage);
    }

    public function findWithRoles($id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }


    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
