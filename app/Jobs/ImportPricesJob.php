<?php

namespace App\Jobs;

use App\File;
use App\Services\Products\Import\ImportFromFile;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportPricesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var File
     */
    protected $file;

    /**
     * ImportPricesJob constructor.
     * @param int $fileId
     */
    public function __construct(int $fileId)
    {
        $this->file = File::findOrFail($fileId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $import = new ImportFromFile($this->file, 'EXCEL');
        $import->handle();
    }
}
