<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateCentral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:central';
    protected $description = 'Run only Central module migrations';

    public function handle()
    {
        $this->call('migrate', [
            '--path' => 'Modules/Central/Database/Migrations',
            '--force' => true,
        ]);
    }
}
