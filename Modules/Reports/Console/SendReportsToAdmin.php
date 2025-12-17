<?php
namespace Modules\Reports\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Modules\Notifications\Emails\AdminReportsMail;
use Modules\Reports\Actions\GenerateBulkReportsAction;
use Modules\Users\Entities\User;
use Exception;

class SendReportsToAdmin extends Command
{
    protected $signature = 'report:send-to-admins {tenant_id : The ID of the tenant to process reports for}';
    protected $description = 'Generate weekly reports and send them via email to all administrators.';

    public function __construct(
        protected GenerateBulkReportsAction $bulkAction
    ) {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle(): int
    {
        $tenantId = $this->argument('tenant_id');

        try {
            tenancy()->initialize($tenantId);

            $reports = $this->bulkAction->execute();

            if ($reports->isEmpty()) {
                $this->warn("No reports were generated for tenant: {$tenantId}");
                return self::SUCCESS;
            }

            $admins = User::role('admin')->get();

            if ($admins->isEmpty()) {
                $this->warn("No admin users found in tenant: {$tenantId}");
                return self::SUCCESS;
            }

            $this->processEmailSending($admins, $reports, $tenantId);

            $this->info("Successfully processed {$reports->count()} reports for {$tenantId}.");

            return self::SUCCESS;

        } catch (Exception $e) {
            $this->error("Critical error in tenant {$tenantId}: " . $e->getMessage());
            return self::FAILURE;
        } finally {
            if (tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }


    protected function processEmailSending(Collection $admins, Collection $reports, string $tenantId): void
    {
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new AdminReportsMail($reports, $tenantId));
            $this->line(" -> Queued email for admin: {$admin->email}");
        }
    }
}
