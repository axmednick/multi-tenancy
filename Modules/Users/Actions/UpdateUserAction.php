<?php

namespace Modules\Users\Actions;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class UpdateUserAction
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function execute(User $user, array $data): User
    {

        $role = $data['role'] ?? null;
        unset($data['role']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->repository->update($user, $data);

        if ($role) {
            $user->syncRoles([$role]);
        }

        return $user;
    }
}
