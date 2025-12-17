<?php
namespace Modules\Tasks\Observers;

use Modules\Tasks\Entities\Task;
use Modules\Tasks\Entities\TaskStatusLog;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function updating(Task $task)
    {
        if ($task->isDirty('status')) {
            TaskStatusLog::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'old_status' => $task->getOriginal('status'),
                'new_status' => $task->status,
            ]);
        }
    }
}
