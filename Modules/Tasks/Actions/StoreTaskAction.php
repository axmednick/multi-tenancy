<?php

namespace Modules\Tasks\Actions;

use Modules\Tasks\Entities\Task;
use Modules\Tasks\Repositories\TaskRepository;

class StoreTaskAction
{
    public function __construct(
        protected TaskRepository $repository
    ) {}

    public function execute(array $data): Task
    {
        return $this->repository->create($data);
    }
}
