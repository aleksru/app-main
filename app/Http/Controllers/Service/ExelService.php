<?php

namespace App\Http\Controllers\Service;

use PHPExcel_IOFactory;

class ExelService
{
    static public function excelToArray($filePath, $header=true)
    {
        //Create excel reader after determining the file type
        $inputFileName = $filePath;
        /**  Identify the type of $inputFileName  **/
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        /**  Create a new Reader of the type that has been identified  **/
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        /** Set read type to read cell data onl **/
        $objReader->setReadDataOnly(true);
        /**  Load $inputFileName to a PHPExcel Object  **/
        $objPHPExcel = $objReader->load($inputFileName);
        //Get worksheet and built array with first row as header
        $objWorksheet = $objPHPExcel->getActiveSheet();
        //excel with first row header, use header as key
        if($header){
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
            $headingsArray = $headingsArray[1];
            $r = -1;
            $namedDataArray = array();
            for ($row = 2; $row <= $highestRow; ++$row) {
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                    ++$r;
                    foreach($headingsArray as $columnKey => $columnHeading) {
                        $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
                    }
                }
            }
        }
        else{
            //excel sheet with no header
            $namedDataArray = $objWorksheet->toArray(null,true,true,true);
        }
        return $namedDataArray;
    }

    static public function excelAllSheetsToArray($filePath, $header=true): \Generator
    {
        //Create excel reader after determining the file type
        $inputFileName = $filePath;
        /**  Identify the type of $inputFileName  **/
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        /**  Create a new Reader of the type that has been identified  **/
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        /** Set read type to read cell data onl **/
        $objReader->setReadDataOnly(true);
        /**  Load $inputFileName to a PHPExcel Object  **/
        $objPHPExcel = $objReader->load($inputFileName);

        foreach ($allSheets = $objPHPExcel->getAllSheets() as $objWorksheet){
            $namedDataArray = [];
            if($header){
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
                $headingsArray = $headingsArray[1];
                $r = -1;
                for ($row = 2; $row <= $highestRow; ++$row) {
                    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                    if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                        ++$r;
                        foreach($headingsArray as $columnKey => $columnHeading) {
                            $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
                        }
                    }
                }
            }
            else{
                //excel sheet with no header
                $namedDataArray = $objWorksheet->toArray(null,true,true,true);
            }
            yield $namedDataArray;
        }
    }
}
