<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Http\Requests\UserRequest;
use Modules\Users\Http\Requests\UserUpdateRequest;
use Modules\Users\Transformers\UserResource;

class UsersController extends Controller
{
    use AuthorizesRequests;


    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        $users = User::with('roles')->paginate(12);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->assignRole($validated['role']);

        return UserResource::make($user);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $this->authorize('view', $user);

        return UserResource::make($user);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = collect($validated)->except('role')->toArray();

        $user->fill($data);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (isset($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return UserResource::make($user);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json(null, 204);
    }
}
