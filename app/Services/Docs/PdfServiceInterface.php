<?php


namespace App\Services\Docs;


interface PdfServiceInterface
{
    /**
     * Return path saved
     * @param string $pathName
     * @return string
     */
    public function save(string $pathName);

    public function stream();

    public function download($fileName);

    public function loadData($data);

    public function setPaper(string $paper, string $orientation);
}