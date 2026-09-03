<?php

namespace App\Services;
use App\Contracts\PdfInterface;
use Barryvdh\DomPDF\Facade\Pdf;


class PdfService implements PDFInterface
{
    public function generate(string $view, array $data): string
    {
        return Pdf::loadView($view, $data)->output();
    }

    public function download(string $view, array $data, string $filename)
    {
        return Pdf::loadView($view, $data)->download($filename);
    }
}