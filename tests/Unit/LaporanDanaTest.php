<?php

namespace Tests\Unit;

use App\Helpers\LaporanDana;
use App\Models\Item;
use App\Models\item_lpj;
use App\Models\Kegiatan;
use App\Models\Sie;
use Tests\TestCase;

class LaporanDanaTest extends TestCase
{
    public function test_pengeluaran_mengelompokkan_per_kwitansi_lalu_tambahan_lalu_proposal(): void
    {
        $konsumsi = $this->sie('Konsumsi', [
            $this->realisasi('Nasi', 9, 15000, bon: 1),
            $this->realisasi('Es Batu', 2, 20000, bon: 1, tambahan: true),
            $this->realisasi('Obat', 2, 5000, bon: null, tambahan: true),
            $this->realisasi('Snack', 10, 8000, bon: 2),
        ], [
            $this->itemProposal('Air Mineral', 5, 40000, bon: null),
            $this->itemProposal('Nasi', 10, 15000, bon: 1),
        ]);
        $kosong = $this->sie('K3', [], []);

        $laporan = LaporanDana::pengeluaran((new Kegiatan())->setRelation('sie', collect([$konsumsi, $kosong])));

        [$sie, $k3] = $laporan['sies'];
        $this->assertSame([1, 'Konsumsi', 5], [$sie['no'], $sie['nama'], $sie['jumlah_item']]);
        $this->assertSame(
            [[175000.0, ['Nasi', 'Es Batu']], [80000.0, ['Snack']], [10000.0, ['Obat']], [200000.0, ['Air Mineral']]],
            array_map(fn ($k) => [$k['total'], array_column($k['items'], 'keterangan')], $sie['kwitansi'])
        );
        $this->assertSame(465000.0, $sie['subtotal']);

        $this->assertSame([2, 'K3', 0, [], 0], [$k3['no'], $k3['nama'], $k3['jumlah_item'], $k3['kwitansi'], $k3['subtotal']]);
        $this->assertSame(465000.0, $laporan['grandTotal']);
        $this->assertSame('Empat Ratus Enam Puluh Lima Ribu Rupiah', $laporan['terbilang']);
    }

    private function sie(string $nama, array $realisasi, array $proposal): Sie
    {
        return (new Sie(['Nama_Sie' => $nama]))
            ->setRelation('item_lpj', collect($realisasi))
            ->setRelation('items', collect($proposal));
    }

    private function realisasi(string $ket, int $qty, int $harga, ?int $bon, bool $tambahan = false): item_lpj
    {
        return (new item_lpj())->forceFill([
            'ID_Bon' => $bon, 'Di_Luar_Proposal' => $tambahan, 'Keterangan' => $ket,
            'Qty_Realisasi' => $qty, 'Satuan_Realisasi' => 'Pcs', 'Harga_Realisasi' => $harga,
            'Total_Realisasi' => $qty * $harga,
        ]);
    }

    private function itemProposal(string $ket, int $qty, int $harga, ?int $bon): Item
    {
        return (new Item())->forceFill([
            'ID_Bon' => $bon, 'Keterangan' => $ket, 'Qty' => $qty, 'Satuan' => 'Pcs',
            'Harga_Unit' => $harga, 'Total' => $qty * $harga,
        ]);
    }
}
