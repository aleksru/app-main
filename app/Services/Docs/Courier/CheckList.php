<?php


namespace App\Services\Docs\Courier;


use App\Services\Docs\BaseDocumentWorker;
use App\Services\Docs\DomPdfService;
use App\Services\Docs\PdfServiceInterface;

class CheckList extends BaseDocumentWorker
{
    const PATH_CHECK_LISTS = self::PATH_DOCS . 'check_lists/';
    private $checkListData;

    public function __construct(PdfServiceInterface $pdfService, CheckListData $checkListData)
    {
        parent::__construct($pdfService);
        $pdfService->setPaper('a4', DomPdfService::ORIENTATION_LANDSCAPE);
        $this->checkListData = $checkListData;
    }

    protected function getSavePath()
    {
        return storage_path(self::PATH_CHECK_LISTS . $this->getFileName());
    }

    protected function renderView(): string
    {
        return $this->renderedDelivery = view('docs.courier.check_list',
            ['checkListData' => $this->checkListData])
            ->render();
    }

    protected function getFileName(): string
    {
        return 'Расписка_' .
            str_replace(' ', '_', $this->checkListData->getCourier()->name) . '_' .
            $this->checkListData->getStrDateDelivery() . '.pdf';
    }
}