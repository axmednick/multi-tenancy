<?php
namespace Modules\Users\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Actions\StoreUserAction;
use Modules\Users\Actions\UpdateUserAction;
use Modules\Users\Http\Requests\UserRequest;
use Modules\Users\Http\Requests\UserUpdateRequest;
use Modules\Users\Transformers\UserResource;

class UsersController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected UserRepository $repository
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = $this->repository->getPaginatedWithRoles(12);

        return UserResource::collection($users);
    }

    public function store(UserRequest $request, StoreUserAction $action): UserResource
    {
        $this->authorize('create', User::class);

        $user = $action->execute($request->validated());

        return UserResource::make($user);
    }

    public function show(int $id): UserResource
    {
        $user = $this->repository->findById($id);

        $this->authorize('view', $user);

        return UserResource::make($user);
    }

    public function update(UserUpdateRequest $request, int $id, UpdateUserAction $action): UserResource
    {
        $user = $this->repository->findById($id);

        $this->authorize('update', $user);

        $updatedUser = $action->execute($user, $request->validated());

        return UserResource::make($updatedUser);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->repository->findById($id);

        $this->authorize('delete', $user);

        $this->repository->delete($user);

        return response()->json(null, 204);
    }
}
