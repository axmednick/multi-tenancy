<?php
namespace Modules\Tasks\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Tasks\Entities\Task;
use Modules\Tasks\Repositories\TaskRepository;
use Modules\Tasks\Actions\UpdateTaskAction;
use Modules\Tasks\Actions\StoreTaskAction;
use Modules\Tasks\Http\Requests\CreateTaskRequest;
use Modules\Tasks\Http\Requests\UpdateTaskRequest;
use Modules\Tasks\Services\TaskService;
use Modules\Tasks\Transformers\TaskResource;

class TasksController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected TaskService $taskService,
        protected TaskRepository $repository
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $this->taskService->getCachedTasks(auth()->user());

        return TaskResource::collection($tasks);
    }

    public function store(CreateTaskRequest $request, StoreTaskAction $action): TaskResource
    {
        $this->authorize('create', Task::class);

        $task = $action->execute($request->validated());

        return new TaskResource($task);
    }

    public function show(int $id): TaskResource
    {
        $task = $this->repository->findById($id);

        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, int $id, UpdateTaskAction $action): TaskResource
    {
        $task = $this->repository->findById($id);

        $this->authorize('update', $task);

        $updatedTask = $action->execute($task, $request->validated(), auth()->user());

        return new TaskResource($updatedTask);
    }

    public function destroy(int $id): JsonResponse
    {
        $task = $this->repository->findById($id);

        $this->authorize('delete', $task);

        $this->repository->delete($task);

        return response()->json(null, 204);
    }
}
