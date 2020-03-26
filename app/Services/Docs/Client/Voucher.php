<?php


namespace App\Services\Docs\Client;


use App\Order;
use App\Services\Docs\BaseDocumentWorker;
use App\Services\Docs\PdfServiceInterface;

abstract class Voucher extends BaseDocumentWorker
{
    const PATH_VOUCHERS = self::PATH_DOCS . 'vouchers/';

    /**
     * @var Order
     */
    protected $order;

    /**
     * Voucher constructor.
     * @param Order $order
     * @param PdfServiceInterface $pdfService
     */
    public function __construct(Order $order, PdfServiceInterface $pdfService)
    {
        parent::__construct($pdfService);
        $this->order = $order;
    }

    protected function getSavePath()
    {
        return storage_path(self::PATH_VOUCHERS . $this->getFileName());
    }
}