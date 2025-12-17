<?php

namespace Modules\Users\Actions;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class StoreUserAction
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function execute(array $data): User
    {
        $role = $data['role'];
        unset($data['role']);

        $data['password'] = Hash::make($data['password']);

        $user = $this->repository->create($data);

        $user->assignRole($role);

        return $user;
    }
}
