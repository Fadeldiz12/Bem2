<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LpjExport implements FromView, ShouldAutoSize, WithStyles
{
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj.bon'])->findOrFail($this->id);
        $sies = $kegiatan->sie;

        // Kita gunakan view khusus excel
        return view('rab.lpj.export-excel', compact('kegiatan', 'sies'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bold pada header baris 1 sampai 3 (sesuaikan dengan tabel)
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
            3    => ['font' => ['bold' => true]],
        ];
    }
}