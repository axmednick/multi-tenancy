<?php

namespace Modules\Tasks\Actions;

use Modules\Tasks\Entities\Comment;
use Modules\Tasks\Entities\Task;
use Modules\Tasks\Repositories\CommentRepository;

class StoreCommentAction
{
    public function __construct(protected CommentRepository $repository) {}

    public function execute(Task $task, array $data): Comment
    {
        $data['user_id'] = auth('sanctum')->id();
        $data['task_id'] = $task->id;

        return $this->repository->create($data);
    }
}
