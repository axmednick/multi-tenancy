<?php
namespace Modules\Reports\Repositories;

use Modules\Reports\Entities\Report;
use Modules\Tasks\Entities\TaskStatusLog;
use Modules\Users\Entities\User;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ReportRepository
{
    public function getCompletedTasks(User $user, Carbon $start, Carbon $end): Collection
    {
        return TaskStatusLog::where('user_id', $user->id)
            ->where('new_status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }

    public function createReportRecord(array $data): Report
    {
        return Report::create($data);
    }
}
