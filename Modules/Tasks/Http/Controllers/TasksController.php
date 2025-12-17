<?php

namespace Modules\Tasks\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Tasks\Entities\Task;
use Modules\Tasks\Http\Requests\CreateTaskRequest;
use Modules\Tasks\Http\Requests\UpdateTaskRequest;
use Modules\Tasks\Service\CacheService;
use Modules\Tasks\Transformers\TaskResource;

class TasksController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected CacheService $cacheService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Task::class);

        $user = auth('sanctum')->user();
        $cacheKey = $this->cacheService->getTenantCacheKey("tasks_list_user_{$user->id}");

        $tasks = Cache::remember($cacheKey, 60, function () use ($user) {
            return Task::query()
                ->when($user->can('tasks.view.own') && ! $user->can('tasks.view.any'), function ($q) use ($user) {
                    $q->where('assigned_to', $user->id);
                })
                ->orderBy('due_date', 'desc')
                ->get();
        });

        return TaskResource::collection($tasks);
    }



    public function store(CreateTaskRequest $request)
    {
        $this->authorize('create', Task::class);


        $validated = $request->validated();
        $task = Task::create($validated);
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('view', $task); // TaskPolicy-dəki view methodu çağırılır

        return new TaskResource($task);
    }


    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('update', $task);

        $validated = $request->validated();


        if (! auth('sanctum')->user()->can('tasks.update.any')) {

            $validated = $request->only('status');
        }

        $task->update($validated);

        return new TaskResource($task);
    }



    public function destroy($id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(null, 204);
    }
}
