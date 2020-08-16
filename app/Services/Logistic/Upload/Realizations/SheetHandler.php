<?php


namespace App\Services\Logistic\Upload\Realizations;



use App\Exceptions\Orders\OrderNotFoundException;
use App\Exceptions\Realizations\Upload\ErrorUpdateEntityException;
use App\Exceptions\Realizations\Upload\ValidateRowException;
use App\Services\Logistic\Upload\Realizations\Interfaces\RowCreatorImp;
use App\Services\Logistic\Upload\Realizations\Interfaces\RowHandlerImp;
use Illuminate\Support\Facades\Log;

class SheetHandler
{
    /**
     * @var RowHandlerImp
     */
    protected $orderRowHandler;
    /**
     * @var RowCreatorImp
     */
    protected $rowCreator;
    /**
     * @var array
     */
    protected $arrRowData;

    /**
     * SheetHandler constructor.
     * @param RowHandlerImp $orderRowHandler
     * @param RowCreatorImp $rowCreator
     * @param array $arrRowData
     */
    public function __construct(RowHandlerImp $orderRowHandler, RowCreatorImp $rowCreator, array $arrRowData)
    {
        $this->orderRowHandler = $orderRowHandler;
        $this->rowCreator = $rowCreator;
        $this->arrRowData = $arrRowData;
    }

    /**
     * @param array $arrRowData
     */
    public function setArrRowData(array $arrRowData): void
    {
        $this->arrRowData = $arrRowData;
    }

    /**
     *
     */
    public function handle()
    {
        foreach ($this->arrRowData as $row){
            try{
                $this->rowCreator->setRowArr($row);
                $this->orderRowHandler->setRow($this->rowCreator->create());
                $this->orderRowHandler->handle();
            }catch (OrderNotFoundException $exception){
                Log::channel('upload_realizations')->error($exception->getMessage());
                continue;
            }catch (ErrorUpdateEntityException $exception){
                Log::channel('upload_realizations')->error($exception->getMessage());
                continue;
            }catch (ValidateRowException $exception){
                Log::channel('upload_realizations')->error($exception->getMessage());
                continue;
            }
        }
    }
}
