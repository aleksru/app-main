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
        //счетчик
        $counter = 0;
        
//        foreach (PriceType::getPriceTypesName() as $priceType){
//           PriceType::firstOrCreate(['name' => $priceType]);
//        }
        
        $files = File::where('status', 0)->get();
        if ( !$files->isEmpty() ) {
            foreach ($files as $file) {
                $priceList = PriceType::where('name', explode('_', $file->name)[0])->first();

                if (!$priceList){
                    Log::error("Не найден прайс: ".$file->name." ИМЯ ПРАЙС-ЛИСТА НЕ ОБНАРУЖЕНО.");
                    //обновляем статус файла - обработан
                    $file->status = 1;
                    $file->save();

                    continue;
                }
                
                //отсоединяем все продукты от прайса
                $priceList->products()->detach();

                //обнуляем счетчик
                $counter = 0;
                
                foreach (ExelService::excelToArray(storage_path('app/'.$file->path)) as $productPriceList) {
                    if (!isset($productPriceList[Product::PRICE_LIST_ARTICUL])|| 
                        !isset($productPriceList[Product::PRICE_LIST_PRODUCT])|| 
                        !isset($productPriceList[Product::PRICE_LIST_PRICE])){
                        Log::error("Ошибка прайс-листа. Отсутстувую свойства в таблице. Имя файла ".$file->name);
                        continue;      
                    }
                    //ищем\создаем сущность продукта
                    $product = Product::firstOrNew(['article' => $productPriceList[Product::PRICE_LIST_ARTICUL]]);
                    $product->product_name = $productPriceList[Product::PRICE_LIST_PRODUCT];
                    $product->save();
                    ++$counter;
                    
                    $product->priceList()->attach($priceList->id, ['price' => $productPriceList[Product::PRICE_LIST_PRICE]]);
                }

                //обновляем версию прайс-листа
                $priceList->version =  $priceList->version + 1;
                $priceList->save();

                //обновляем статус файла - обработан
                $file->status = 1;
                $file->save();

                Log::error("Обновление прайса ".$priceList->name." завершено. Обновлено: ".$counter." товаров.");
            }
        }

        Log::error("Обновление цен завершено.");
    }
}
