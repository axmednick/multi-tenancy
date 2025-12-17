<?php

namespace Modules\Reports\Actions;

use Illuminate\Support\Collection;
use Modules\Users\Entities\User;

class GenerateBulkReportsAction
{
    public function __construct(
        protected GenerateReportAction $generateReportAction
    ) {}

    public function execute(?string $startDate = null, ?string $endDate = null): Collection
    {
        $users = User::all();
        $reports = collect();

        foreach ($users as $user) {
            $report = $this->generateReportAction->execute($user, $startDate, $endDate);
            $reports->push($report);
        }

        return $reports;
    }
}
