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
 * Tabel dana (LPJ / Proposal) dalam .docx — tampilan sama dengan export PDF/Excel.
 * $laporan dari App\Helpers\LaporanDana::pengeluaran() atau anggaran().
 * Sel gabungan memakai merge asli Word, jadi Word sendiri yang merapikan saat tabel pindah halaman.
 */
class TabelDanaWordExport extends WordExport
{
    private const UNGU = '6A1B9A';
    private const UNGU_MUDA = 'E3D4F7';

    /** @var int[] lebar kolom dalam twip */
    private array $lebar;

    public function __construct(private array $laporan)
    {
        $lebarTabel = self::pt(390);
        $this->lebar = array_map(fn ($persen) => (int) round($lebarTabel * $persen / 100), $laporan['lebarKolom']);
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

        $gayaJudul = ['bold' => true, 'size' => 12];
        foreach ($this->laporan['judul'] as $i => $judul) {
            $section->addText($judul, $gayaJudul, $i === 0
                ? ['spaceAfter' => self::pt(8)]
                : ['spaceAfter' => self::pt(9), 'indentation' => ['left' => self::pt(21)]]);
        }

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMarginLeft' => self::pt(2),
            'cellMarginRight' => self::pt(2),
            'layout' => TableStyle::LAYOUT_FIXED,
            'indent' => new TblWidth(self::pt($this->laporan['indentTabel']), TblWidthType::TWIP),
        ]);

        $kolomKwitansi = $this->laporan['kolomKwitansi'];
        $terakhir = count($this->lebar) - 1;

        $kepala = ['No', 'Jenis Pengeluaran', 'Keterangan', 'Qty', 'Satuan', 'Harga/Unit (@)', 'Total'];
        if ($kolomKwitansi) {
            $kepala[] = 'Total Kwitansi';
        }
        $this->baris($table);
        foreach ($kepala as $i => $teks) {
            $this->sel($table, $this->lebar[$i], $teks, ['bgColor' => self::UNGU], ['bold' => true, 'color' => 'FFFFFF']);
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
                    if ($kolomKwitansi) {
                        $this->sel(
                            $table,
                            $this->lebar[7],
                            $k === 0 ? LaporanDana::rupiah($kwitansi['total']) : null,
                            $gabungKwitansi ? ['vMerge' => $k === 0 ? 'restart' : 'continue'] : []
                        );
                    }
                    $r++;
                }
            }

            // SUBTOTAL menutupi kolom Keterangan s.d. kolom sebelum kolom nilai terakhir
            $rentang = $terakhir - 2;
            $this->baris($table);
            $this->selSie($table, $sie, $r);
            $this->sel($table, array_sum(array_slice($this->lebar, 2, $rentang)), 'SUBTOTAL', ['gridSpan' => $rentang, 'bgColor' => self::UNGU_MUDA], ['bold' => true]);
            $this->sel($table, $this->lebar[$terakhir], LaporanDana::rupiah($sie['subtotal']), ['bgColor' => self::UNGU_MUDA], ['bold' => true]);
        }

        $totalGaya = ['bgColor' => self::UNGU];
        $totalFont = ['bold' => true, 'color' => 'FFFFFF'];
        $this->baris($table);
        $this->sel($table, array_sum(array_slice($this->lebar, 0, $terakhir)), $this->laporan['labelTotal'], ['gridSpan' => $terakhir] + $totalGaya, $totalFont);
        $this->sel($table, $this->lebar[$terakhir], LaporanDana::rupiah($this->laporan['grandTotal']), $totalGaya, $totalFont);

        $terbilang = $section->addTextRun([
            'alignment' => Jc::BOTH,
            'lineHeight' => 1.5,
            'spaceBefore' => self::pt(14),
            'indentation' => ['left' => self::pt($this->laporan['indentTerbilang'])],
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
