<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\File;
use App\PriceType;
use Illuminate\Support\Facades\Log;

class UpdatePriceListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-price-lists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update price lists';

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
        /**
         * добавление новых прайсов
         */
        foreach (PriceType::getPriceTypesName() as $priceType){
           PriceType::firstOrCreate(['name' => $priceType]);
        }
        
        $files = File::where('status', 0)->get();
        if ( !$files->isEmpty() ) {
            foreach ($files as $file) {
                if ( !in_array(explode('_', $file->name)[0], PriceType::getPriceTypesName() )){
                    Log::error("Не найден прайс: ".$file->name." ИМЯ ПРАЙС ЛИСТА НЕ ОБНАРУЖЕНО. см config/app/price_types");
                    continue;
                }
                
                
            }
        }
    }
}
