<?php

namespace App\Exports;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Converter;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class WordExport
{
    abstract protected function build(PhpWord $word): void;

    public function download(string $filename): StreamedResponse
    {
        // Tanpa ini PhpWord menulis teks mentah ke XML: "&" / "<" dari input user merusak file .docx
        Settings::setOutputEscapingEnabled(true);

        $word = new PhpWord();
        $this->build($word);

        return response()->streamDownload(
            fn () => IOFactory::createWriter($word, 'Word2007')->save('php://output'),
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        );
    }

    protected static function pt(float $point): int
    {
        return (int) round(Converter::pointToTwip($point));
    }

    protected static function cm(float $cm): int
    {
        return (int) round(Converter::cmToTwip($cm));
    }
}
