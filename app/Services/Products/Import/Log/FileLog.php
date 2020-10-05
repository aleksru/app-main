<?php


namespace App\Services\Products\Import\Log;


use App\Services\Products\Import\LogImportImp;
use Illuminate\Support\Facades\Log;

class FileLog implements LogImportImp
{
    function log($data)
    {
        Log::channel('import_prices')->error($data);
    }
}
