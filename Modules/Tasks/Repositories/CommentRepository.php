<?php

namespace Modules\Tasks\Repositories;

use Modules\Tasks\Entities\Task;
use Modules\Tasks\Entities\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository
{
    public function getCommentsByTask(Task $task): Collection
    {
        return $task->comments()->with('user')->latest()->get();
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }
}
