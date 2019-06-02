<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Traits\ExcelTemplateServiceTrait;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Parts\CorporateInfo;

abstract class BaseReport implements DataInterface
{
    use ExcelTemplateServiceTrait;

    /**+
     * @var array
     */
    protected $data = [];

    /**
     * Get document
     */
    public function getData() : void
    {
        $this->download($this->getFileName(), $this->getTemplatePath(), $this->data);
    }


    protected function setCorpInfo() : void
    {
        $corpInfo = (new CorporateInfo())->getPart();
        foreach ($corpInfo as $key => $value) {
            $this->data[str_replace('.', '_', $key)] = $value;
        }
    }

    /**
     * @return array
     */
    public function getArrayData() : array
    {
        return $this->data;
    }

}