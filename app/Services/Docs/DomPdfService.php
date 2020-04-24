<?php


namespace App\Services\Docs;


use Illuminate\Support\Facades\App;

class DomPdfService implements PdfServiceInterface
{
    private $service;
    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_LANDSCAPE = 'landscape';

    /**
     * DomPdfService constructor.
     */
    public function __construct()
    {
        $this->service = App::make('dompdf.wrapper');
        $this->service->setOptions(
            [
                'dpi' => 110,
                'fontDir' => public_path('fonts'),
                'defaultFont' => 'sans-serif',
            ])->setPaper('a3');
    }

    public function save(string $path)
    {
        $this->service->save($path);
    }

    public function stream()
    {
        return $this->service->stream();
    }

    public function loadData($data)
    {
        $this->service->loadHTML($data);
    }

    public function download($fileName)
    {
        return $this->service->download($fileName);
    }

    public function setPaper(string $paper, string $orientation = self::ORIENTATION_PORTRAIT)
    {
        $this->service->setPaper($paper, $orientation);
    }
}