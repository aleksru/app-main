<?php

namespace App\Http\Controllers\Service\DocumentBuilder\Services;

use App\Http\Controllers\Service\DocumentBuilder\Services\ExcelServiceInterface;

class ExcelTemplateService implements ExcelServiceInterface {

    const TAG_MARKER = '%';
    const PATTERN = '/' . self::TAG_MARKER . '([^' . self::TAG_MARKER . ']+)' . self::TAG_MARKER . '/';

    /** @var  \PHPExcel $template */
    private $template;

    /** @var array $dataSource */
    private $dataSource = [];

    /**
     * @param string $templateFile path to xls file
     * @param array $dataSource
     * @param string $outputFormat PDF or XLSX
     * @return string file content
     */
    public function generateDocument($templateFile, $dataSource, $outputFormat = 'PDF')
    {
        $outputFormat = mb_strtoupper($outputFormat);
        $this->template = \PHPExcel_IOFactory::load($templateFile);
        $this->template->setActiveSheetIndex(0);
        $this->prepareDataSource($dataSource);
        $interatRow = $this->template->getActiveSheet()->getRowIterator();

        foreach ($interatRow as $row) {//$startRow = 1, $endRow = 100
            $arrayTag = FALSE;
            /** @var \PHPExcel_Cell $cell */

            foreach ($row->getCellIterator() as $cell) {

                $value = $cell->getValue();
                if (empty($value)) {
                    continue;
                }

                if ($value instanceof \PHPExcel_RichText) {
                    $value = $value->getPlainText();
                }

                $tags = $this->extractTags($value);

                if ( !$tags ) {
                    continue;
                }


                foreach ($tags as $tag) {

                    if ($tag['type'] == 'array') {

                        if (!isset($this->dataSource[$tag['parent_name']])) {
                            continue;
                        }

                        $tagData = $this->dataSource[$tag['parent_name']];

                        if (!$arrayTag && $tagData['__length'] - 1 > $tagData['__current_index']) {
                            $this->duplicateRow($row->getRowIndex());
                            $interatRow->resetEnd();
                        }
                        $currentData = $tagData[$tagData['__current_index']];

                        if (isset($currentData[$tag['name']])) {
                            $value = str_replace($tag['template_name'], $currentData[$tag['name']], $value);
                        }
                        $arrayTag = $tag['parent_name'];
                    }
                    elseif ($tag['type'] == 'image' && isset($this->dataSource[$tag['parent_name']][$tag['name']])) {
                        $image_path = $this->dataSource[$tag['parent_name']][$tag['name']];
                        if (exif_imagetype($image_path) == IMAGETYPE_PNG) {
                            $gdImage = imagecreatefrompng($image_path);
                            $renderingFunc = \PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG;
                        }
                        else if (exif_imagetype($image_path) == IMAGETYPE_JPEG) {
                            $gdImage = imagecreatefromjpeg($image_path);
                            $renderingFunc = \PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG;
                        }
                        else {
                            // не поддерживаемый формат изображения, выбросить Exception ?
                            continue;
                        }

                        $imageObj = new \PHPExcel_Worksheet_MemoryDrawing();
                        $imageObj->setName($tag['name'] . $row->getRowIndex() . $cell->getColumn());
                        $imageObj->setImageResource($gdImage);
                        $imageObj->setRenderingFunction($renderingFunc);
                        $imageObj->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
//
//            // Подгонка размеров изображения под ячейку для формата xls.
//            // При экспорте в эксель изображения если они больше перекрывают другие ячейки.
//            // Решение ниже нужно доделывать, что бы размер вычислялся по объединенным ячейкам.
//
////            list($imageWidth, $imageHeight) = getimagesize($image_path);
////            $heightColumn = $this->template->getActiveSheet()->getRowDimension($row->getRowIndex())->getRowHeight();
////            if ($imageHeight > $heightColumn) {
////              $newWidth = $imageWidth * $heightColumn / $imageHeight;
////              $imageObj->setWidth($newWidth);
////            }
//
                        $imageObj->setCoordinates($cell->getColumn() . $row->getRowIndex());
                        $imageObj->setWorksheet($this->template->getActiveSheet());
                        $value = str_replace($tag['template_name'], '', $value);


                    }
                    elseif (isset($this->dataSource[$tag['name']])) {
                        $value = str_replace($tag['template_name'], $this->dataSource[$tag['name']], $value);
                    }
                }

                $cell->setValue($value);
            }

            if ($arrayTag) {
                $this->dataSource[$arrayTag]['__current_index']++;
            }
        }

        return $this->makeContent($outputFormat);
    }

    /**
     * @param $fileName
     * @param $templateFile
     * @param $dataSource
     * @param string $outputFormat
     */
    public function download($fileName, $templateFile, $dataSource, $outputFormat = 'PDF') {
        $outputFormat = mb_strtoupper($outputFormat);
        $fileContent = $this->generateDocument($templateFile, $dataSource, $outputFormat);

        if ($outputFormat == 'PDF') {
            header('Content-Type: application/pdf');
        }
        else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        }

        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        echo $fileContent;
        exit;
    }

    /**
     * @param int $row_index
     */
    private function duplicateRow($row_index) {
        $heighestRow = $this->template->getActiveSheet()->getHighestRow();
        $heighestColumn = $this->template->getActiveSheet()->getHighestColumn();
        $columnNumber = \PHPExcel_Cell::columnIndexFromString($heighestColumn);
        $this->template->getActiveSheet()->insertNewRowBefore($row_index + 1, 1);
        $this->copyRows($heighestRow, $heighestRow + 1, 1, $columnNumber);
        $this->copyRows($row_index, $row_index + 1, 1, $columnNumber);
    }

    /**
     * @param int $srcRow
     * @param int $dstRow
     * @param int $height
     * @param int $width
     */
    private function copyRows($srcRow, $dstRow, $height, $width) {
        /** @var \PHPExcel_Worksheet $sheet */
        $sheet = $this->template->getActiveSheet();
        for ($row = 0; $row < $height; $row++) {
            for ($col = 0; $col < $width; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $srcRow + $row);
                $style = $sheet->getStyleByColumnAndRow($col, $srcRow + $row);
                $dstCell = \PHPExcel_Cell::stringFromColumnIndex($col) . (string) ($dstRow + $row);
                $sheet->setCellValue($dstCell, $cell->getValue());
                $sheet->duplicateStyle($style, $dstCell);
            }

            $h = $sheet->getRowDimension($srcRow + $row)->getRowHeight();
            $sheet->getRowDimension($dstRow + $row)->setRowHeight($h);
        }

        foreach ($sheet->getMergeCells() as $mergeCell) {
            $mc = explode(":", $mergeCell);
            $col_s = preg_replace("/[0-9]*/", "", $mc[0]);
            $col_e = preg_replace("/[0-9]*/", "", $mc[1]);
            $row_s = ((int) preg_replace("/[A-Z]*/", "", $mc[0])) - $srcRow;
            $row_e = ((int) preg_replace("/[A-Z]*/", "", $mc[1])) - $srcRow;

            if (0 <= $row_s && $row_s < $height) {
                $merge = $col_s . (string) ($dstRow + $row_s) . ":" . $col_e . (string) ($dstRow + $row_e);
                $sheet->mergeCells($merge);
            }
        }
    }

    /**
     * @param array $dataSource
     */
    private function prepareDataSource($dataSource) {
        foreach ($dataSource as $tag => &$value) {
            if (is_array($value)) {
                $value['__length'] = count($value);
                $value['__current_index'] = 0;
            }
        }
        $this->dataSource = $dataSource;
    }

    /**
     * @param string $str
     * @return array
     */
    private function extractTags($str) {
        preg_match_all(self::PATTERN, $str, $matches);
        if (isset($matches[1])) {
            $tags = [];
            foreach ($matches[1] as $tag) {
                $parts_tag = explode('.', $tag);
                if (isset($parts_tag[1]) && $parts_tag[0] == 'img') {
                    $type = 'image';
                }
                elseif (isset($parts_tag[1])) {
                    $type = 'array';
                }
                else {
                    $type = 'simple';
                }
                $tags[] = [
                    'name' => $tag,
                    'template_name' => self::TAG_MARKER . $tag . self::TAG_MARKER,
                    'type' => $type,
                    'is_composite' => isset($parts_tag[1]) ? TRUE : FALSE,
                    'parent_name' => $parts_tag[0],
                    'child_name' => isset($parts_tag[1]) ? $parts_tag[1] : FALSE,
                ];
            }
            return $tags;
        }
        return [];
    }

    /**
     * @param string $fileType
     * @return string
     */
    private function makeContent($fileType) {
        if ($fileType == 'PDF') {
            $rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
            \PHPExcel_Settings::setPdfRendererName($rendererName);
            \PHPExcel_Settings::setPdfRendererPath(base_path() . '/vendor/mpdf/mpdf');
            define('_MPDF_TTFONTDATAPATH', sys_get_temp_dir()."/");
            $objWriter = new \PHPExcel_Writer_PDF($this->template);
        }
        else {
            $objWriter = \PHPExcel_IOFactory::createWriter($this->template, 'Excel2007');
        }

        ob_start();
        $objWriter->save('php://output');
        return ob_get_clean();

    }
}