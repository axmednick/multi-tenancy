<?php

namespace Modules\Reports\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Reports\Actions\GenerateReportAction;
use Modules\Reports\Transformers\ReportResource;

class ReportsController extends Controller
{
    /**
     * @param Request $request
     * @param GenerateReportAction $action
     * @return ReportResource
     */
    public function generateReport(Request $request, GenerateReportAction $action): ReportResource
    {
        $user = auth('sanctum')->user();

        $report = $action->execute(
            $user,
            $request->query('start_date'),
            $request->query('end_date')
        );

        return ReportResource::make($report);
    }
}
