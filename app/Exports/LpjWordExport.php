<?php

namespace App\Exports;

use App\Helpers\LaporanDana;
use PhpOffice\PhpWord\ComplexType\TblWidth;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth as TblWidthType;
use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * Laporan Dana "B. Pengeluaran" (LPJ) dalam .docx — tampilan sama dengan export PDF/Excel.
 * Sel gabungan memakai merge asli Word, jadi Word sendiri yang merapikan saat tabel pindah halaman.
 */
class LpjWordExport extends WordExport
{
    private const LEBAR_KOLOM = [4.4, 17.4, 15.2, 6.3, 10.2, 14.8, 15.2, 16.5];
    private const UNGU = '6A1B9A';
    private const UNGU_MUDA = 'E3D4F7';

    /** @var int[] lebar kolom dalam twip */
    private array $lebar;

    public function __construct(private array $laporan)
    {
        $lebarTabel = self::pt(390);
        $this->lebar = array_map(fn ($persen) => (int) round($lebarTabel * $persen / 100), self::LEBAR_KOLOM);
    }

    protected function build(PhpWord $word): void
    {
        $word->setDefaultFontName('Times New Roman');
        $word->setDefaultFontSize(12);

        $section = $word->addSection([
            'paperSize' => 'A4',
            'marginTop' => self::cm(2.5),
            'marginBottom' => self::cm(2.5),
            'marginLeft' => self::cm(3),
            'marginRight' => self::cm(2.5),
        ]);

        $judul = ['bold' => true, 'size' => 12];
        $section->addText('XII. LAPORAN DANA', $judul, ['spaceAfter' => self::pt(8)]);
        $section->addText('B. PENGELUARAN', $judul, ['spaceAfter' => self::pt(9), 'indentation' => ['left' => self::pt(21)]]);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMarginLeft' => self::pt(2),
            'cellMarginRight' => self::pt(2),
            'layout' => TableStyle::LAYOUT_FIXED,
            'indent' => new TblWidth(self::pt(36), TblWidthType::TWIP),
        ]);

        $this->baris($table);
        foreach (['No', 'Jenis Pengeluaran', 'Keterangan', 'Qty', 'Satuan', 'Harga/Unit (@)', 'Total', 'Total Kwitansi'] as $i => $kepala) {
            $this->sel($table, $this->lebar[$i], $kepala, ['bgColor' => self::UNGU], ['bold' => true, 'color' => 'FFFFFF']);
        }

        foreach ($this->laporan['sies'] as $sie) {
            $r = 0;

            foreach ($sie['kwitansi'] as $kwitansi) {
                $gabungKwitansi = count($kwitansi['items']) > 1;

                foreach ($kwitansi['items'] as $k => $item) {
                    $this->baris($table);
                    $this->selSie($table, $sie, $r);
                    $this->sel($table, $this->lebar[2], $item['keterangan']);
                    $this->sel($table, $this->lebar[3], (string) $item['qty']);
                    $this->sel($table, $this->lebar[4], $item['satuan']);
                    $this->sel($table, $this->lebar[5], LaporanDana::rupiah($item['harga']));
                    $this->sel($table, $this->lebar[6], LaporanDana::rupiah($item['total']));
                    $this->sel(
                        $table,
                        $this->lebar[7],
                        $k === 0 ? LaporanDana::rupiah($kwitansi['total']) : null,
                        $gabungKwitansi ? ['vMerge' => $k === 0 ? 'restart' : 'continue'] : []
                    );
                    $r++;
                }
            }

            $this->baris($table);
            $this->selSie($table, $sie, $r);
            $this->sel($table, array_sum(array_slice($this->lebar, 2, 5)), 'SUBTOTAL', ['gridSpan' => 5, 'bgColor' => self::UNGU_MUDA], ['bold' => true]);
            $this->sel($table, $this->lebar[7], LaporanDana::rupiah($sie['subtotal']), ['bgColor' => self::UNGU_MUDA], ['bold' => true]);
        }

        $totalGaya = ['bgColor' => self::UNGU];
        $totalFont = ['bold' => true, 'color' => 'FFFFFF'];
        $this->baris($table);
        $this->sel($table, array_sum(array_slice($this->lebar, 0, 7)), 'TOTAL REALISASI DANA KEGIATAN', ['gridSpan' => 7] + $totalGaya, $totalFont);
        $this->sel($table, $this->lebar[7], LaporanDana::rupiah($this->laporan['grandTotal']), $totalGaya, $totalFont);

        $terbilang = $section->addTextRun([
            'alignment' => Jc::BOTH,
            'lineHeight' => 1.5,
            'spaceBefore' => self::pt(14),
            'indentation' => ['left' => self::pt(21)],
        ]);
        $terbilang->addText('Terbilang: ', ['bold' => true, 'size' => 12]);
        $terbilang->addText($this->laporan['terbilang'].'.', ['bold' => true, 'italic' => true, 'size' => 12]);
    }

    private function baris(Table $table): void
    {
        $table->addRow(self::pt(19.5), ['cantSplit' => true]);
    }

    /** Kolom No & Jenis Pengeluaran: satu sel gabungan per Sie, termasuk baris SUBTOTAL-nya. */
    private function selSie(Table $table, array $sie, int $r): void
    {
        $gaya = ['bgColor' => self::UNGU_MUDA];
        if ($sie['jumlah_item'] > 0) {
            $gaya['vMerge'] = $r === 0 ? 'restart' : 'continue';
        }

        $this->sel($table, $this->lebar[0], $r === 0 ? (string) $sie['no'] : null, $gaya);
        $this->sel($table, $this->lebar[1], $r === 0 ? $sie['nama'] : null, $gaya);
    }

    private function sel(Table $table, int $lebar, ?string $teks, array $gayaSel = [], array $gayaFont = []): void
    {
        $table->addCell($lebar, ['valign' => 'center', 'noWrap' => false] + $gayaSel)
            ->addText($teks ?? '', ['size' => 9] + $gayaFont, ['alignment' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 0]);
    }
}
