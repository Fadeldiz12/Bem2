<?php

namespace App\Helpers;

use App\Models\Kegiatan;
use Dompdf\Canvas;
use Dompdf\Frame;
use DOMElement;

class LaporanDana
{
    /**
     * Data tabel "B. Pengeluaran" (Laporan Dana LPJ), dipakai export PDF & Excel.
     * Butuh relasi sie.items dan sie.item_lpj sudah di-eager-load.
     *
     * Per Sie, item dikelompokkan per kwitansi: item_lpj per ID_Bon (realisasi),
     * lalu item tambahan yang belum punya bon, lalu item RAB yang belum punya bon
     * (keduanya masing-masing jadi kwitansi sendiri).
     */
    public static function pengeluaran(Kegiatan $kegiatan): array
    {
        $sies = [];
        $grandTotal = 0;

        foreach ($kegiatan->sie->values() as $index => $sie) {
            $kwitansi = [];

            $realisasi = fn ($item) => self::baris(
                $item->Keterangan,
                $item->Qty_Realisasi,
                $item->Satuan_Realisasi,
                $item->Harga_Realisasi,
                $item->Total_Realisasi,
            );
            [$denganBon, $tanpaBon] = $sie->item_lpj->partition(fn ($item) => $item->ID_Bon !== null);

            foreach ($denganBon->groupBy('ID_Bon') as $items) {
                $kwitansi[] = [
                    'total' => (float) $items->sum('Total_Realisasi'),
                    'items' => $items->map($realisasi)->values()->all(),
                ];
            }

            // Item tambahan yang kwitansinya belum dihubungkan: satu baris masing-masing
            foreach ($tanpaBon as $item) {
                $kwitansi[] = [
                    'total' => (float) $item->Total_Realisasi,
                    'items' => [$realisasi($item)],
                ];
            }

            foreach ($sie->items->whereNull('ID_Bon') as $item) {
                $kwitansi[] = [
                    'total' => (float) $item->Total,
                    'items' => [self::baris($item->Keterangan, $item->Qty, $item->Satuan, $item->Harga_Unit, $item->Total)],
                ];
            }

            $subtotal = array_sum(array_column($kwitansi, 'total'));
            $grandTotal += $subtotal;

            $sies[] = [
                'no' => $index + 1,
                'nama' => $sie->Nama_Sie,
                'subtotal' => $subtotal,
                'jumlah_item' => array_sum(array_map(fn ($k) => count($k['items']), $kwitansi)),
                'kwitansi' => $kwitansi,
            ];
        }

        return [
            'sies' => $sies,
            'grandTotal' => $grandTotal,
            'terbilang' => Terbilang::rupiah($grandTotal),
        ];
    }

    public static function rupiah(float|int|string|null $nominal): string
    {
        return 'Rp '.number_format((float) $nominal, 0, ',', '.');
    }

    /**
     * Callback dompdf untuk export PDF: kolom gabungan tidak punya garis antar-baris, jadi saat
     * tabel terpotong halaman sisi bawahnya terbuka. dompdf tidak mengulang tfoot, maka garis
     * penutup digambar manual di bawah baris tabel (tr.baris) terakhir di setiap halaman.
     */
    public static function pdfCallbacks(): array
    {
        $barisTerakhir = null;

        return [
            ['event' => 'end_frame', 'f' => function (Frame $frame) use (&$barisTerakhir) {
                $node = $frame->get_node();
                if ($node instanceof DOMElement && $node->nodeName === 'tr'
                    && in_array('baris', explode(' ', $node->getAttribute('class')), true)) {
                    $barisTerakhir = $frame->get_border_box();
                }
            }],
            ['event' => 'end_page_render', 'f' => function (Frame $frame, Canvas $canvas) use (&$barisTerakhir) {
                if ($barisTerakhir) {
                    [$x, $y, $w, $h] = $barisTerakhir;
                    $canvas->line($x, $y + $h, $x + $w, $y + $h, [0, 0, 0], 0.75);
                }
                $barisTerakhir = null;
            }],
        ];
    }

    private static function baris(?string $keterangan, $qty, ?string $satuan, $harga, $total): array
    {
        return [
            'keterangan' => $keterangan,
            'qty' => $qty,
            'satuan' => $satuan,
            'harga' => (float) $harga,
            'total' => (float) $total,
        ];
    }
}
