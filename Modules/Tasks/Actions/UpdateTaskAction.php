<?php

namespace Modules\Tasks\Actions;

use Modules\Tasks\Entities\Task;
use Modules\Tasks\Repositories\TaskRepository;

class UpdateTaskAction
{
    public function __construct(protected TaskRepository $repository) {}

    public function execute(Task $task, array $data, $user): Task
    {
        $version = (int) $data['version'];

        if (!$user->can('tasks.update.any')) {
            $data = array_intersect_key($data, array_flip(['status', 'version']));
        }
        $updateData = collect($data)->except(['version'])->toArray();
        $updateData['version'] = $version + 1;

        $affectedRows = $this->repository->updateWithVersionCheck($task, $updateData, $version);

        if ($affectedRows === 0) {
            throw new \Exception("Məlumat başqa bir istifadəçi tərəfindən yenilənib.");
        }

        return $task->refresh();
    }
}
