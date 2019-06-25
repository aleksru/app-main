<?php


namespace App\Console\Commands;

use App\Services\Metro\UpdateStations;
use Illuminate\Console\Command;

class UpdateMetroStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metro:update-stations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update metro stations';

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
    public function handle()
    {
        (new UpdateStations())->update();
    }
}