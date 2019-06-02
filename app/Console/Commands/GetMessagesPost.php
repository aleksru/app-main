<?php

namespace App\Console\Commands;

use App\Services\IMAP\AvitoGetMessage;
use App\Services\ParseMailAvito;
use App\Services\Parsers\AvitoMessage;
use Illuminate\Console\Command;

class GetMessagesPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avito:mail-parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse e-mail avito';

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
    public function handle(ParseMailAvito $parseMailAvito)
    {
        $parseMailAvito->process();
    }
}
