<?php

namespace Modules\Tasks\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Tasks\Repositories\TaskRepository;
use Modules\Tasks\Services\CacheService;

class TaskService
{
    public function __construct(
        protected TaskRepository $repository,
        protected CacheService $cacheService
    ) {}

    public function getCachedTasks($user)
    {
        $cacheKey = $this->cacheService->getTenantCacheKey("tasks_list_user_{$user->id}");

        return Cache::remember($cacheKey, 60, function () use ($user) {
            return $this->repository->getTasksForUser($user);
        });
    }
}
