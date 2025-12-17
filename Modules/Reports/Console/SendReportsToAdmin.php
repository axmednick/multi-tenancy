<?php

namespace Modules\Reports\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Modules\Notifications\Emails\AdminReportsMail;
use Modules\Reports\Service\ReportService;
use Modules\Users\Entities\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SendReportsToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:send-to-admins {tenant_id : The ID of the tenant to process reports for}';    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected ReportService $reportService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        try {
            tenancy()->initialize($tenantId);


            $reports = $this->reportService->generate();

            if ($reports->isEmpty()) {
                $this->warn("No reports were generated for tenant {$tenantId} this week.");
                tenancy()->end();
                return Command::SUCCESS;
            }

            $this->info("Successfully generated {$reports->count()} reports.");

            $admins = User::role('admin')->get();

            if ($admins->isEmpty()) {
                $this->warn("No 'admin' users found in tenant {$tenantId}. Reports generated but not sent.");
                tenancy()->end();
                return Command::SUCCESS;
            }


            $this->sendReportsToAdmins($admins, $reports);

            $this->info("Reports successfully sent to " . $admins->count() . " admin(s) in {$tenantId}.");

            tenancy()->end();

        } catch (\Exception $e) {
            $this->error("Error processing tenant {$tenantId}: " . $e->getMessage());


            return Command::FAILURE;
        }

        return Command::SUCCESS;

    }

    protected function sendReportsToAdmins(Collection $admins, Collection $reports): void
    {
        foreach ($admins as $admin) {
            Mail::to($admin->email)
                ->queue(new AdminReportsMail($reports, $this->argument('tenant_id')));

            $this->line(" -> Sent mail to: {$admin->email}");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['tenant_id', InputArgument::REQUIRED, 'The ID of the tenant to process.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
