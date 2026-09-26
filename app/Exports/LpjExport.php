<?php

namespace App\Exports;

use App\Helpers\LaporanDana;
use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LpjExport implements FromView, WithColumnWidths, WithStyles
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj'])->findOrFail($this->id);
        $laporan = LaporanDana::pengeluaran($kegiatan);

        return view('rab.lpj.export-excel', compact('kegiatan', 'laporan'));
    }

    public function columnWidths(): array
    {
        // Proporsi kolom mengikuti tabel "B. Pengeluaran" di dokumen Laporan Dana
        return ['A' => 5, 'B' => 18, 'C' => 20, 'D' => 7, 'E' => 11, 'F' => 15, 'G' => 16, 'H' => 17];
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
