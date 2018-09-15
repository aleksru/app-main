<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\File;
use App\Product;
use App\PriceType;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Service\ExelService;

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
                $priceList = PriceType::where('name', explode('_', $file->name)[0])->first();
                //открыть для исключения файла из последующих обработок
//                    //обновляем статус файла - обработан
//                    $file->status = 1;
//                    $file->save();

                if (!$priceList){
                    Log::error("Не найден прайс: ".$file->name." ИМЯ ПРАЙС ЛИСТА НЕ ОБНАРУЖЕНО. см config/app/price_types");
                    //обновляем статус файла - обработан
                    $file->status = 1;
                    $file->save();
                    continue;
                }
                
                //отсоединяем все продукты от прайса
                $priceList->products()->detach();
                
                foreach (ExelService::excelToArray(storage_path('app/'.$file->path)) as $productPriceList) {
                    if (!isset($productPriceList[Product::PRICE_LIST_ARTICUL])|| 
                        !isset($productPriceList[Product::PRICE_LIST_PRODUCT])|| 
                        !isset($productPriceList[Product::PRICE_LIST_PRICE])){
                        Log::error("НЕ НАЙДЕНО СВОЙСТВО В ФАЙЛЕ ".$file->name." ".$productPriceList[Product::PRICE_LIST_ARTICUL]);
                        continue;      
                    }
                    //ищем\создаем сущность продукта
                    $product = Product::firstOrNew(['article' => $productPriceList[Product::PRICE_LIST_ARTICUL]]);
                    $product->product_name = $productPriceList[Product::PRICE_LIST_PRODUCT];
                    $product->save();
                    
                    $product->priceList()->attach($priceList->id, ['price' => $productPriceList[Product::PRICE_LIST_PRICE]]);
                }
                
                //обновляем статус файла - обработан
                $file->status = 1;
                $file->save();
            }
        }
    }
}
