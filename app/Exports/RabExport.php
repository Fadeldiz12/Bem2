<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RabExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $kegiatan = Kegiatan::with(['sie.items'])->findOrFail($this->id);
        $sies = $kegiatan->sie;

        return view('rab.proposal.export-excel', compact('kegiatan', 'sies'));
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            3    => ['font' => ['bold' => true]],
        ];
    }
}