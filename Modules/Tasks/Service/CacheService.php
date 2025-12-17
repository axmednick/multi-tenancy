<?php

namespace Modules\Tasks\Service;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function getTenantCacheKey(string $key): string
    {
        $tenantId = tenancy()->tenant->id ?? 'central';
        return "tenant:{$tenantId}:{$key}";
    }

    public function clearUserTaskCache(int $userId)
    {
        $cacheKey = $this->getTenantCacheKey("tasks_list_user_{$userId}");
        Cache::forget($cacheKey);
    }

}
