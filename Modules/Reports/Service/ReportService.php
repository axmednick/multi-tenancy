<?php

namespace Modules\Reports\Service;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Modules\Reports\Entities\Report;
use Modules\Tasks\Entities\TaskStatusLog;
use Modules\Users\Entities\User;
use PDF;


class ReportService
{

    public function generate(?int $userId = null, ?string $startDate = null, ?string $endDate = null)
    {

        $users = $this->resolveUsers($userId);


        [$start, $end] = $this->resolveDateRange($startDate, $endDate);

        $reports = collect();


        foreach ($users as $user) {
            $report = $this->generateSingleReport($user, $start, $end);
            $reports->push($report);
        }

        return $userId ? $reports->first() : $reports;
    }

    private function resolveUsers(?int $userId): Collection
    {
        return $userId ? User::where('id', $userId)->get() : User::all();
    }

    private function resolveDateRange(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->startOfWeek();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfWeek();

        return [$start, $end];
    }


    private function generateSingleReport(User $user, Carbon $start, Carbon $end)
    {

        $tasks = $this->fetchReportData($user, $start, $end);


        $filePath = $this->exportReportFile($user, $tasks, $start, $end);

        return $this->createReportRecord($user, $filePath);
    }


    private function fetchReportData(User $user, Carbon $start, Carbon $end): Collection
    {
        return TaskStatusLog::where('user_id', $user->id)
            ->where('new_status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }

    private function exportReportFile(User $user, Collection $tasks, Carbon $start, Carbon $end): string
    {

        $pdf = PDF::loadView('reports::reports.report_view', [
            'tasks' => $tasks,
            'user' => $user,
            'start' => $start,
            'end' => $end,
        ]);

        $tenantId = tenant('id');

        $filePath = "{$tenantId}/reports/report_{$user->id}_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}.pdf";

        Storage::disk('tenant')->put($filePath, $pdf->output());

        return $filePath;
    }


    private function createReportRecord(User $user, string $filePath)
    {
        return Report::create([
            'user_id' => $user->id,
            'type' => 'custom',
            'status' => 'generated',
            'file_path' => $filePath,
        ]);
    }

}
