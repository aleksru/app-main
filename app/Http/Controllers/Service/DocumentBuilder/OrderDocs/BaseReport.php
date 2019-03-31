<?php

namespace App\Http\Controllers\Service\DocumentBuilder\OrderDocs;


use App\Http\Controllers\Service\DocumentBuilder\DataInterface;
use App\Http\Controllers\Service\DocumentBuilder\OrderDocs\Traits\ExcelTemplateServiceTrait;

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

}