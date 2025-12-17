<?php

namespace Modules\Tasks\Repositories;

use Modules\Tasks\Entities\Task;

class TaskRepository
{
    public function getTasksForUser($user)
    {
        return Task::query()
            ->when($user->can('tasks.view.own') && !$user->can('tasks.view.any'), function ($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })
            ->orderBy('due_date', 'desc')
            ->get();
    }

    public function findById(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function updateWithVersionCheck(Task $task, array $data, int $version): int
    {
        return $task->newQuery()
            ->where('id', $task->id)
            ->where('version', $version)
            ->update($data);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
