<?php

namespace App\Http\Controllers\Rab;

use App\Http\Controllers\Controller;

use App\Models\item_lpj;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Item tambahan LPJ: kebutuhan mendadak saat acara yang tidak ada di proposal.
 * Disimpan hanya di item_lpj (Di_Luar_Proposal = true), tabel Item (proposal) tidak disentuh.
 * Kwitansi (Bon) opsional dan bisa dihubungkan belakangan.
 */
class ItemLpjController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validasi($request, (int) $request->input('sie_id'), true);

        item_lpj::create([
            'ID_Sie' => $data['sie_id'],
            'ID_Bon' => $data['bon_id'] ?? null,
            'Di_Luar_Proposal' => true,
            ...$this->kolomRealisasi($data),
        ]);

        return redirect()->back()->with('success', 'Item tambahan berhasil ditambahkan.');
    }

    public function update(Request $request, item_lpj $itemLpj)
    {
        if (! $itemLpj->Di_Luar_Proposal) {
            return redirect()->back()->with('error', 'Realisasi item proposal diubah lewat tombol "Lihat / Edit Bon".');
        }

        $data = $this->validasi($request, $itemLpj->ID_Sie);
        $bonLama = $itemLpj->bon;

        $itemLpj->update([
            'ID_Bon' => $data['bon_id'] ?? null,
            ...$this->kolomRealisasi($data),
        ]);

        $pesan = 'Item tambahan berhasil diperbarui.';
        if ($bonLama && $bonLama->ID_Bon != $itemLpj->ID_Bon && $bonLama->hapusJikaKosong()) {
            $pesan .= " Kwitansi \"{$bonLama->Nama_Bon}\" ikut dihapus karena sudah tidak berisi item.";
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function destroy(item_lpj $itemLpj)
    {
        if (! $itemLpj->Di_Luar_Proposal) {
            return redirect()->back()->with('error', 'Realisasi item proposal dihapus lewat tombol "Lihat / Edit Bon".');
        }

        $bon = $itemLpj->bon;
        $itemLpj->delete();

        $pesan = 'Item tambahan berhasil dihapus.';
        if ($bon && $bon->hapusJikaKosong()) {
            $pesan .= " Kwitansi \"{$bon->Nama_Bon}\" ikut dihapus karena sudah tidak berisi item.";
        }

        return redirect()->back()->with('success', $pesan);
    }

    private function validasi(Request $request, int $sieId, bool $baru = false): array
    {
        return $request->validate([
            'sie_id' => $baru ? 'required|exists:Sie,ID_Sie' : 'nullable',
            'Jenis_Pengeluaran' => 'required|string|max:255',
            'Keterangan' => 'required|string|max:255',
            'Qty' => 'required|integer|min:1',
            'Satuan' => 'required|string|max:100',
            'Harga' => 'required|numeric|min:0',
            // Kwitansi opsional, tapi kalau diisi harus milik Sie yang sama
            'bon_id' => ['nullable', Rule::exists('Bon', 'ID_Bon')->where('ID_Sie', $sieId)],
        ]);
    }

    private function kolomRealisasi(array $data): array
    {
        return [
            'Jenis_Pengeluaran' => $data['Jenis_Pengeluaran'],
            'Keterangan' => $data['Keterangan'],
            'Qty_Realisasi' => $data['Qty'],
            'Satuan_Realisasi' => $data['Satuan'],
            'Harga_Realisasi' => $data['Harga'],
        ];
    }
}
