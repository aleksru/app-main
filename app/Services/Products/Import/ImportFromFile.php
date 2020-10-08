<?php


namespace App\Services\Products\Import;


use App\File;
use App\PriceType;
use App\Services\Products\Import\Handlers\ExcelHandler;
use App\Services\Products\Import\Log\FileLog;
use Illuminate\Support\Facades\File as FileFacade;

class ImportFromFile
{
    /**
     * @var File
     */
    protected $file;

    /**
     * @var LogImportImp
     */
    protected $log;

    /**
     * @var ImportHandlerImp
     */
    protected $handler;

    /**
     * ImportFromFile constructor.
     * @param File $file
     * @param string $type
     * @throws \Exception
     */
    public function __construct(File $file, string $type)
    {
        $this->file = $file;
        $this->initLog();
        $this->handler = self::handlerFactory($type);
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $this->log->log("Обновление цен. Старт " . $this->file->name);
            $this->file->setProcessing();
            $priceList = $this->file->priceList;
            if( ! $priceList ){
                $this->file->setProcessed();
                $this->log->log("Прайс не найден!");
                throw new \Exception('Price list not found!');
            }
            /**
             * @var PriceType $priceList
             */
            $priceList->clearPrices();
            $this->handler->handle(new ImportProductPrice($priceList), storage_path('app/') . $this->file->path);
            $priceList->increment('version');
            $this->file->setProcessed();
            $this->file->setCountProcessed($this->handler->getCountImports());
            FileFacade::delete(storage_path('app/') . $this->file->path);
            $this->log->log("Обновление прайса " . $priceList->name . " завершено. Обновлено: ". $this->handler->getCountImports() . " товаров.");
        }catch (\Exception $exception){
            $this->log->log("Произошла ошибка в обработке файла " . $priceList->name);
            $this->log->log($exception->getMessage());
            $this->file->setError();
        }
    }

    /**
     * @param string $type
     * @return ImportHandlerImp
     * @throws \Exception
     */
    public static function handlerFactory(string $type): ImportHandlerImp
    {
        if($type === 'EXCEL'){
            return new ExcelHandler;
        }
        throw new \Exception('Handler not found');
    }

    /**
     *
     */
    private function initLog()
    {
        $this->log = new FileLog;
    }
}
