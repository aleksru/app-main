<?php


namespace App\Jobs;


use App\Models\FileRealization;
use App\Services\Logistic\Upload\Realizations\FileRealizationHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FileRealizationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var FileRealization
     */
    protected $fileRealization;

    /**
     * @var int
     */
    public $tries = 2;

    /**
     * @var int
     */
    public $timeout = 600;

    /**
     * FileRealizationJob constructor.
     * @param $fileRealization
     */
    public function __construct(FileRealization $fileRealization)
    {
        $this->fileRealization = $fileRealization;
        $this->onQueue('files');
    }


    public function handle()
    {
        (new FileRealizationHandler($this->fileRealization))->handle();
    }
}
