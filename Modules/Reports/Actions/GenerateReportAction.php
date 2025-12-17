<?php
namespace Modules\Reports\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Reports\Repositories\ReportRepository;
use Modules\Users\Entities\User;
use PDF;

class GenerateReportAction
{
    public function __construct(protected ReportRepository $repository) {}

    public function execute(User $user, ?string $startDate, ?string $endDate)
    {
        [$start, $end] = $this->resolveDateRange($startDate, $endDate);

        $tasks = $this->repository->getCompletedTasks($user, $start, $end);

        $filePath = $this->storePdf($user, $tasks, $start, $end);

        return $this->repository->createReportRecord([
            'user_id'   => $user->id,
            'type'      => 'custom',
            'status'    => 'generated',
            'file_path' => $filePath,
        ]);
    }

    private function resolveDateRange(?string $start, ?string $end): array
    {
        return [
            $start ? Carbon::parse($start)->startOfDay() : now()->startOfWeek(),
            $end ? Carbon::parse($end)->endOfDay() : now()->endOfWeek()
        ];
    }

    private function storePdf($user, $tasks, $start, $end): string
    {
        $pdf = PDF::loadView('reports::reports.report_view', compact('tasks', 'user', 'start', 'end'));

        $tenantId = tenant('id');
        $filePath = "{$tenantId}/reports/report_{$user->id}_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}.pdf";

        Storage::disk('tenant')->put($filePath, $pdf->output());

        return $filePath;
    }
}
