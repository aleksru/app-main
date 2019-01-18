<?php

namespace App\Console\Commands;

use App\Log;
use App\Logging\Services\PrepareLogs;
use Illuminate\Console\Command;

class PrepareLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logs string prepare';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PrepareLogs $prepareLogs)
    {
        $prepareLogs->setLogs(Log::noPrepared()->get());
        $prepareLogs->prepare();
    }
}
