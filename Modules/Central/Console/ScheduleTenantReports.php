<?php

namespace Modules\Central\Console;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Modules\Central\Entities\TenantReportSchedule;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScheduleTenantReports extends Command
{
    protected $signature = 'tenants:schedule-reports';
    protected $description = 'Bütün tenantlar üçün hesabat göndərilməsi ehtiyacını yoxlayır, qeyd edir və icra edir.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Tenant report scheduler başladı.');

        $allTenants = Tenant::all();

        $processedCount = 0;

        foreach ($allTenants as $tenant) {
            $tenantId = $tenant->id;

            $schedule = TenantReportSchedule::firstOrNew(
                ['tenant_id' => $tenantId],
                ['frequency' => 'weekly']
            );

            if ($schedule->needsExecution()) {
                $this->line("Tenant {$tenantId} üçün report icra edilir...");

                try {
                    Artisan::call('report:send-to-admins', [
                        'tenant_id' => $tenantId
                    ], $this->output);

                    $schedule->last_executed_at = Carbon::now();
                    $schedule->executed_count = $schedule->executed_count + 1;
                    $schedule->save();

                    $this->info(" -> Tenant {$tenantId} reportları uğurla işə salındı. (İcra sayı: {$schedule->executed_count})");
                    $processedCount++;

                } catch (\Exception $e) {
                    $this->error(" -> Tenant {$tenantId} üçün report icra edilərkən səhv: " . $e->getMessage());
                }
            } else {
                $lastExecution = $schedule->last_executed_at ? $schedule->last_executed_at->toDateTimeString() : 'Heç vaxt';
                $this->line("Tenant {$tenantId} üçün report icrasına ehtiyac yoxdur. (Son icra: {$lastExecution})");
            }
        }

        $this->info("Report dövrü başa çatdı. İcra olunan tenant sayı: {$processedCount}");
        return Command::SUCCESS;
    }

    protected function getArguments()
    {
        return [];
    }

    protected function getOptions()
    {
        return [];
    }
}
