<?php


namespace App\Services\Docs;


abstract class BaseDocumentWorker
{
    const PATH_DOCS = 'app/docs/';

    /**
     * @var PdfServiceInterface
     */
    private $pdfService;

    /**
     * @var bool
     */
    private $isLoadedData = false;

    /**
     * BaseDocumentWorker constructor.
     * @param PdfServiceInterface $pdfService
     */
    public function __construct(PdfServiceInterface $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function stream()
    {
        $this->loadData();
        return $this->pdfService->stream();
    }

    public function save()
    {
        $path = $this->getSavePath();
        $this->loadData();
        $this->pdfService->save($path);

        return $path;
    }

    public function download()
    {
        $this->loadData();
        return $this->pdfService->download($this->getFileName());
    }

    private function loadData()
    {
        if( ! $this->isLoadedData ){
            $this->pdfService->loadData($this->renderView());
            $this->isLoadedData = true;
        }
    }

    abstract protected function getSavePath();

    abstract protected function renderView() : string ;

    abstract protected function getFileName() : string ;
}