<?php


namespace App\Services\Products\Import\Excel;


use App\Services\Products\Import\Data\ProductDTO;
use App\Services\Products\Import\ImportProductPrice;
use App\Services\Products\Import\ImportProductPriceImp;
use App\Services\Products\Prices\Import\PriceDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Throwable;

class ProductImport implements ToCollection, WithChunkReading, WithStartRow, SkipsOnError
{
    /**
     * @var ImportProductPrice
     */
    protected $importService;

    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * ProductImport constructor.
     * @param ImportProductPriceImp $importProductPrice
     */
    public function __construct(ImportProductPriceImp $importProductPrice)
    {
        $this->importService = $importProductPrice;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row){
            $validator = Validator::make($row->toArray(), [
                '0' => 'required|string',
                '1' => 'required|string',
                '2' => 'required|numeric',
                '3' => 'numeric|nullable',
            ]);
            if ($validator->fails()){
                Log::channel('import_prices')->error($validator->errors());
                continue;
            }
            $this->importService->import(
                new ProductDTO($row[0], $row[1]),
                new PriceDTO($row[0], (float)$row[2], (empty($row[3]) ? null : (float)$row[3]))
            );
            $this->counter++;
        }
    }

    public function onError(Throwable $e)
    {
        Log::channel('import_prices')->error($e->getMessage());
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }
}
