<?php


namespace App\Services\Logistic\Upload\Realizations;

use App\Enums\FileStatusesEnums;
use App\Http\Controllers\Service\ExelService;
use App\Models\FileRealization;
use App\Services\Logistic\Upload\Realizations\Data\RowCreatorFromArray;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileRealizationHandler
{
    /**
     * @var FileRealization
     */
    protected $fileRealization;

    /**
     * FileRealizationHandler constructor.
     * @param FileRealization $fileRealization
     */
    public function __construct(FileRealization $fileRealization)
    {
        $this->fileRealization = $fileRealization;
    }

    public function handle()
    {
        try {
            Log::channel('upload_realizations')->error('Начало обработки файла ' . $this->fileRealization->name);
            $this->updStatusFile(FileStatusesEnums::PROCESS);
            $arrSheets = ExelService::excelAllSheetsToArray(storage_path('app/'. $this->fileRealization->path));
            $orderRowHandler = new OrderRowHandler();
            $rowCreator = new RowCreatorFromArray();
            $sheetHandler = new SheetHandler($orderRowHandler, $rowCreator, []);
            foreach ($arrSheets as $sheet){
                $sheetHandler->setArrRowData($sheet);
                $sheetHandler->handle();
            }
            $this->updStatusFile(FileStatusesEnums::SUCCESS);
        }catch (\Exception $exception){
            $this->updStatusFile(FileStatusesEnums::ERROR);
            Log::channel('upload_realizations')->error($exception);
            throw new \Exception($exception);
        } finally {
            Log::channel('upload_realizations')->error('Конец обработки файла ' . $this->fileRealization->name);
            Storage::disk('local')->delete($this->fileRealization->path);
        }
    }

    private function updStatusFile(int $status)
    {
        $this->fileRealization->status = $status;
        $this->fileRealization->save();
    }
}
