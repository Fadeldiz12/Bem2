<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Tabel dana (LPJ / Proposal) dalam .xlsx. $laporan dari App\Helpers\LaporanDana::pengeluaran() atau anggaran().
 */
class TabelDanaExport implements FromView, WithColumnWidths, WithStyles
{
    public function __construct(private array $laporan)
    {
    }

    public function view(): View
    {
        return view('rab.export.tabel-dana-excel', ['laporan' => $this->laporan]);
    }

    public function columnWidths(): array
    {
        // Proporsi kolom mengikuti tabel "B. Pengeluaran" di dokumen Laporan Dana
        if ($this->laporan['kolomKwitansi']) {
            return ['A' => 5, 'B' => 18, 'C' => 20, 'D' => 7, 'E' => 11, 'F' => 15, 'G' => 16, 'H' => 17];
        }

        // Tanpa kolom Total Kwitansi (proposal): lebarnya dipindah ke Keterangan
        return ['A' => 5, 'B' => 18, 'C' => 37, 'D' => 7, 'E' => 11, 'F' => 15, 'G' => 16];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getFont()->setName('Times New Roman');

        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        return [];
    }
}
