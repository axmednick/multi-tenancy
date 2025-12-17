<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Reports\Service\ReportService;
use Modules\Reports\Transformers\ReportResource;

class ReportsController extends Controller
{

    public function __construct(protected ReportService $reportService)
    {
    }

    public function generateReport(Request $request)
    {
        $userId = auth('sanctum')->id();
        $start  = $request->query('start_date');
        $end    = $request->query('end_date');

        $report = $this->reportService->generate($userId, $start, $end);


        return ReportResource::make($report);
    }
}
