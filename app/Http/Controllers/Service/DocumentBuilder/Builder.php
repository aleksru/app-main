<?php

namespace App\Http\Controllers\Service\DocumentBuilder;


use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;

class Builder
{
    /**
     * @param DataInterface $data
     */
    public function download(DataInterface $data)
    {
        $data->prepareData()->getData();
    }
}