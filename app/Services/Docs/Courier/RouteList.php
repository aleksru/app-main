<?php


namespace App\Services\Docs\Courier;


use App\Services\Docs\BaseDocumentWorker;
use App\Services\Docs\DomPdfService;
use App\Services\Docs\PdfServiceInterface;

class RouteList extends BaseDocumentWorker
{
    const PATH_ROUTE_LISTS = self::PATH_DOCS . 'route_lists/';

    /**
     * @var RouteListData
     */
    private $routeListData;

    public function __construct(PdfServiceInterface $pdfService, RouteListData $routeListData)
    {
        parent::__construct($pdfService);
        $pdfService->setPaper('a4', DomPdfService::ORIENTATION_LANDSCAPE);
        $this->routeListData = $routeListData;
    }

    protected function getSavePath()
    {
        return storage_path(self::PATH_ROUTE_LISTS . $this->getFileName());
    }

    protected function renderView(): string
    {
        return $this->renderedDelivery = view('docs.courier.route_list',
            ['routeListData' => $this->routeListData])
            ->render();
    }

    protected function getFileName(): string
    {
        return 'Маршрутный лист_' .
            str_replace(' ', '_', $this->routeListData->getCourier()) . '_' .
            $this->routeListData->getDateDelivery() . '.pdf';
    }

}