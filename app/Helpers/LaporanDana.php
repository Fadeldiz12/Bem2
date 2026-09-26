<?php

namespace App\Helpers;

use App\Models\Item;
use App\Models\Kegiatan;
use App\Models\Sie;
use Dompdf\Canvas;
use Dompdf\Frame;
use DOMElement;

/**
 * Data tabel dana (format dokumen "Laporan Dana - B. Pengeluaran") untuk export PDF, Excel & Word.
 * pengeluaran() = LPJ (realisasi), anggaran() = Proposal (RAB). Keduanya menghasilkan struktur yang sama,
 * sehingga ketiga renderer (resources/views/rab/export/*, App\Exports\TabelDana*) dipakai bersama.
 */
class LaporanDana
{
    /**
     * LPJ. Butuh relasi sie.items dan sie.item_lpj sudah di-eager-load.
     *
     * Per Sie, item dikelompokkan per kwitansi: item_lpj per ID_Bon (realisasi),
     * lalu item tambahan yang belum punya bon, lalu item RAB yang belum punya bon
     * (keduanya masing-masing jadi kwitansi sendiri).
     */
    public static function pengeluaran(Kegiatan $kegiatan): array
    {
        $laporan = self::susun($kegiatan, function (Sie $sie) {
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
                    'items' => [self::itemProposal($item)],
                ];
            }

            return $kwitansi;
        });

        return $laporan + [
            'judul' => ['XII. LAPORAN DANA', 'B. PENGELUARAN'],
            'kolomKwitansi' => true,
            'labelTotal' => 'TOTAL REALISASI DANA KEGIATAN',
            // Proporsi kolom (%) dan posisi (pt) mengikuti dokumen Laporan Dana
            'lebarKolom' => [4.4, 17.4, 15.2, 6.3, 10.2, 14.8, 15.2, 16.5],
            'indentTabel' => 36,
            'indentTerbilang' => 21,
        ];
    }

    /**
     * Proposal (RAB). Butuh relasi sie.items sudah di-eager-load.
     *
     * Proposal belum punya kwitansi, jadi kolom Total Kwitansi tidak ditampilkan dan semua
     * item satu Sie cukup jadi satu grup. Tanpa judul: dokumen langsung dimulai dari tabel.
     */
    public static function anggaran(Kegiatan $kegiatan): array
    {
        $laporan = self::susun($kegiatan, function (Sie $sie) {
            $items = $sie->items->map(fn ($item) => self::itemProposal($item))->values()->all();

            return $items ? [['total' => (float) array_sum(array_column($items, 'total')), 'items' => $items]] : [];
        });

        return $laporan + [
            'judul' => [],
            'kolomKwitansi' => false,
            'labelTotal' => 'TOTAL ANGGARAN DANA KEGIATAN',
            // Lebar kolom Total Kwitansi dipindah ke Keterangan; tabel di tengah halaman
            'lebarKolom' => [4.4, 17.4, 31.7, 6.3, 10.2, 14.8, 15.2],
            'indentTabel' => 25,
            'indentTerbilang' => 25,
        ];
    }

    private static function susun(Kegiatan $kegiatan, callable $kwitansiPerSie): array
    {
        $sies = [];
        $grandTotal = 0;

        foreach ($kegiatan->sie->values() as $index => $sie) {
            $kwitansi = $kwitansiPerSie($sie);
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

    private static function itemProposal(Item $item): array
    {
        return self::baris($item->Keterangan, $item->Qty, $item->Satuan, $item->Harga_Unit, $item->Total);
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
