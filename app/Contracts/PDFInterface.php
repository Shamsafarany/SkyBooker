<?php

namespace App\Contracts;

interface PDFInterface
{
    public function generate(string $view, array $data): string;

    public function download(string $view, array $data, string $filename);
}
