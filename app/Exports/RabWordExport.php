<?php

namespace App\Exports;

use App\Models\Kegiatan;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Table as TableStyle;

/**
 * RAB Proposal dalam .docx — meniru export PDF proposal (resources/views/rab/proposal/export-pdf.blade.php).
 */
class RabWordExport extends WordExport
{
    private const LEBAR_KOLOM = [6, 16, 21, 7, 10, 20, 20];
    private const BIRU = '4285F4';
    private const BIRU_MUDA = 'CFE2F3';
    private const FONT = ['size' => 9];

    /** @var int[] lebar kolom dalam twip */
    private array $lebar;

    public function __construct(private Kegiatan $kegiatan)
    {
        $lebarTabel = self::cm(17);
        $this->lebar = array_map(fn ($persen) => (int) round($lebarTabel * $persen / 100), self::LEBAR_KOLOM);
    }

    protected function build(PhpWord $word): void
    {
        $word->setDefaultFontName('Arial');
        $word->setDefaultFontSize(9);

        $section = $word->addSection([
            'paperSize' => 'A4',
            'marginTop' => self::cm(2),
            'marginBottom' => self::cm(2),
            'marginLeft' => self::cm(2),
            'marginRight' => self::cm(2),
        ]);

        $judul = $section->addTable(['borderSize' => 6, 'borderColor' => self::BIRU, 'layout' => TableStyle::LAYOUT_FIXED]);
        $judul->addRow();
        $selJudul = $judul->addCell(array_sum($this->lebar), ['bgColor' => self::BIRU, 'valign' => 'center']);
        foreach (['RENCANA ANGGARAN BIAYA (RAB)', 'KEGIATAN '.mb_strtoupper($this->kegiatan->Nama_Kegiatan)] as $i => $teks) {
            $selJudul->addText($teks, ['bold' => true, 'size' => 12, 'color' => 'FFFFFF'], [
                'alignment' => Jc::CENTER,
                'spaceBefore' => $i === 0 ? self::pt(6) : 0,
                'spaceAfter' => $i === 1 ? self::pt(6) : 0,
            ]);
        }
        $section->addText('', self::FONT, ['spaceAfter' => self::pt(6)]);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => self::BIRU,
            'cellMargin' => self::pt(4),
            'layout' => TableStyle::LAYOUT_FIXED,
        ]);

        $this->baris($table);
        foreach (['No', 'Jenis Pengeluaran', 'Keterangan', 'Qty', 'Satuan', 'Harga/Unit (@)', 'Total'] as $i => $kepala) {
            $this->sel($table, $this->lebar[$i], $kepala, Jc::CENTER, ['bgColor' => self::BIRU], ['bold' => true, 'color' => 'FFFFFF']);
        }

        $no = 1;
        $grandTotal = 0;
        $grup = ['bgColor' => self::BIRU_MUDA];

        foreach ($this->kegiatan->sie as $sie) {
            $items = $sie->items->values();

            if ($items->isEmpty()) {
                $this->baris($table);
                $this->sel($table, $this->lebar[0], (string) $no++, Jc::CENTER, $grup);
                $this->sel($table, $this->lebar[1], $sie->Nama_Sie, Jc::START, $grup);
                $this->sel($table, array_sum(array_slice($this->lebar, 2)), 'Belum ada item anggaran di sie ini', Jc::CENTER, ['gridSpan' => 5], ['bold' => true]);

                continue;
            }

            $subtotal = 0;
            foreach ($items as $i => $item) {
                $total = $item->Qty * $item->Harga_Unit;
                $subtotal += $total;
                $merge = $items->count() > 1 ? ['vMerge' => $i === 0 ? 'restart' : 'continue'] : [];

                $this->baris($table);
                $this->sel($table, $this->lebar[0], $i === 0 ? (string) $no++ : null, Jc::CENTER, $grup + $merge);
                $this->sel($table, $this->lebar[1], $i === 0 ? $sie->Nama_Sie : null, Jc::START, $grup + $merge);
                $this->sel($table, $this->lebar[2], $item->Keterangan, Jc::START);
                $this->sel($table, $this->lebar[3], (string) $item->Qty, Jc::CENTER);
                $this->sel($table, $this->lebar[4], $item->Satuan, Jc::CENTER);
                $this->sel($table, $this->lebar[5], self::rupiah($item->Harga_Unit), Jc::END);
                $this->sel($table, $this->lebar[6], self::rupiah($total), Jc::END);
            }
            $grandTotal += $subtotal;

            $this->baris($table);
            $this->sel($table, array_sum(array_slice($this->lebar, 0, 6)), 'Sub Total', Jc::END, ['gridSpan' => 6] + $grup, ['bold' => true]);
            $this->sel($table, $this->lebar[6], self::rupiah($subtotal), Jc::END, $grup, ['bold' => true]);
        }

        $this->baris($table);
        $this->sel($table, array_sum(array_slice($this->lebar, 0, 6)), 'Total Keseluruhan', Jc::CENTER, ['gridSpan' => 6] + $grup, ['bold' => true]);
        $this->sel($table, $this->lebar[6], self::rupiah($grandTotal), Jc::END, $grup, ['bold' => true]);

        $this->tandaTangan($section);
    }

    private function tandaTangan($section): void
    {
        $section->addText('', self::FONT, ['spaceAfter' => self::pt(20)]);

        $sepertiga = (int) round(array_sum($this->lebar) / 3);
        $ttd = $section->addTable(['layout' => TableStyle::LAYOUT_FIXED]);
        $ttd->addRow(null, ['cantSplit' => true]);

        foreach ([['Mengetahui,', 'Ketua Panitia'], null, ['', 'Bendahara']] as $kolom) {
            $sel = $ttd->addCell($sepertiga);
            if ($kolom === null) {
                continue;
            }

            foreach ([...$kolom, '', '', ''] as $baris) {
                $sel->addText($baris, self::FONT, ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
            }
            $sel->addText('( .................................... )', ['bold' => true] + self::FONT, ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
        }
    }

    private function baris(Table $table): void
    {
        $table->addRow(null, ['cantSplit' => true]);
    }

    private function sel(Table $table, int $lebar, ?string $teks, string $rata, array $gayaSel = [], array $gayaFont = []): void
    {
        $table->addCell($lebar, ['valign' => 'center', 'noWrap' => false] + $gayaSel)
            ->addText($teks ?? '', self::FONT + $gayaFont, ['alignment' => $rata, 'spaceBefore' => 0, 'spaceAfter' => 0]);
    }

    private static function rupiah(float|int|string|null $nominal): string
    {
        return 'Rp'.number_format((float) $nominal, 2, ',', '.');
    }
}
