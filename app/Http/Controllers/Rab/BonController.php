<?php

namespace App\Http\Controllers\Rab;

use App\Http\Controllers\Controller;

use App\Models\Bon;
use App\Models\Item;
use App\Models\item_lpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('baru_keterangan')) {
            $request->validate([
                'kegiatan_id' => 'required',
                'sie_id' => 'required',
                'nama_bon' => 'required|string|max:255',
                'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'item_ids' => 'array',
                'item_ids.*' => 'exists:Item,ID_Item',
                'realisasi_qty' => 'array',
                'realisasi_qty.*' => 'numeric',
                'realisasi_harga' => 'array',
                'realisasi_harga.*' => 'numeric',
                'realisasi_satuan' => 'array',
            ]);
        } else {
            $request->validate([
                'kegiatan_id' => 'required',
                'sie_id' => 'required',
                'nama_bon' => 'required|string|max:255',
                'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'item_ids' => 'required|array',
                'item_ids.*' => 'exists:Item,ID_Item',
                'realisasi_qty' => 'required|array',
                'realisasi_qty.*' => 'numeric',
                'realisasi_harga' => 'required|array',
                'realisasi_harga.*' => 'numeric',
                'realisasi_satuan' => 'required|array',
            ]);
        }

        DB::beginTransaction();
        try {
            $bon = new Bon;
            $bon->ID_Sie = $request->sie_id; // WAJIB diisi karena dipakai untuk relasi
            $bon->Nama_Bon = $request->nama_bon;

            // Foto opsional: hanya diproses kalau user benar-benar upload file
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/bon'), $fileName);
                $bon->Foto_Bon = $fileName; // Ubah dari Bukti_Pembayaran jadi Foto_Bon sesuai Model
            }

            $bon->save();

            if ($request->item_ids != null) {
                foreach ($request->item_ids as $itemId) {
                    $item = Item::findOrFail($itemId);
                    $item->ID_Bon = $bon->ID_Bon;
                    $item->save();

                    $qtyRealisasi = $request->realisasi_qty[$itemId];
                    $hargaRealisasi = $request->realisasi_harga[$itemId];

                    DB::table('item_lpj')->insert([
                        'ID_Sie' => $request->sie_id, // Wajib sesuai migration
                        'ID_Bon' => $bon->ID_Bon,
                        'Jenis_Pengeluaran' => $item->Jenis_Pengeluaran,
                        'Keterangan' => $item->Keterangan, // Keterangan harus K besar (case-sensitive)
                        'Qty_Realisasi' => $qtyRealisasi,
                        'Satuan_Realisasi' => $request->realisasi_satuan[$itemId], // Typo pemanggilan input
                        'Harga_Realisasi' => $hargaRealisasi,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 4. PROSES INPUT ITEM BARU (DI LUAR ANGGARAN PROPOSAL) jika ada
            if ($request->has('baru_keterangan')) {
                foreach ($request->baru_keterangan as $key => $keterangan) {
                    // A. Buat record Item baru di tabel Item anggaran
                    // $newItem = new Item;
                    // $newItem->ID_Sie = $request->sie_id; // Wajib diisi!
                    // $newItem->Jenis_Pengeluaran = $request->baru_jenis[$key];
                    // $newItem->Keterangan = $keterangan;
                    // $newItem->Qty = $request->baru_qty[$key];
                    // $newItem->Satuan = $request->baru_satuan[$key];
                    // $newItem->Harga_Unit = $request->baru_harga[$key];
                    // $newItem->ID_Bon = $bon->ID_Bon;
                    // $newItem->save();

                    // B. Catat LPJ-nya juga agar sesuai format Migration
                    DB::table('item_lpj')->insert([
                        'ID_Sie' => $request->sie_id,
                        'ID_Bon' => $bon->ID_Bon,
                        'Jenis_Pengeluaran' => $request->baru_jenis[$key],
                        'Keterangan' => $keterangan,
                        'Qty_Realisasi' => $request->baru_qty[$key],
                        'Satuan_Realisasi' => $request->baru_satuan[$key],
                        'Harga_Realisasi' => $request->baru_harga[$key],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('lpj.show', ['id' => $request->kegiatan_id])->with('success', 'Bon berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollback();

            // Jika ada kolom database yang typo, error aslinya akan tampil di sini
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat Bon: '.$e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Endpoint JSON dipakai modal "Edit Bon" (fetch AJAX) di rab.lpj.show —
     * mengembalikan item yang sudah terhubung, item yang tersedia, dan item
     * baru (di luar RAB awal) untuk Bon tertentu.
     */
    public function getDetail(Bon $bon)
    {
        // 1. Ambil item yang sudah terhubung dengan Bon ini
        $linkedItems = Item::where('ID_Bon', '=', $bon->ID_Bon, 'and')->get();

        $itemLpj = item_lpj::where('ID_Bon', '=', $bon->ID_Bon, 'and')->get();

        // 2. Ambil item yang BELUM terhubung ke Bon manapun, TAPI di dalam Sie yang sama
        $availableItems = Item::where('ID_Sie', '=', $bon->ID_Sie, 'and')->whereNull('ID_Bon')->get();

        // Cari item_lpj yang belum ada padanannya di rencana awal (Item)
        $newItemsLpj = item_lpj::where('ID_Bon', '=', $bon->ID_Bon, 'and')->get()->filter(function ($item) {
            return $item->isNew;
        })->values();

        return response()->json([
            'bon' => $bon,
            'linked_items' => $linkedItems,
            'item_lpj' => $itemLpj,
            'available_items' => $availableItems,
            'new_items_lpj' => $newItemsLpj,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bon $bon)
    {
        $request->validate([
            'nama_bon' => 'required|string|max:255',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Nullable karena bisa jadi tidak ganti foto
            'item_ids' => 'array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Master Bon
            $bon->Nama_Bon = $request->nama_bon;

            // Jika ada foto baru yang diupload
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/bon'), $fileName);

                // Hapus foto lama untuk menghemat storage
                if ($bon->Foto_Bon && File::exists(public_path('uploads/bon/'.$bon->Foto_Bon))) {
                    File::delete(public_path('uploads/bon/'.$bon->Foto_Bon));
                }
                $bon->Foto_Bon = $fileName;
            }
            $bon->save();

            // 2. Kelola Item (Tambah / Hapus centang)
            $submittedItemIds = $request->item_ids ?? [];

            // A. Hapus kaitan item yang di-uncheck (tidak dikirim di request)
            $existingItems = Item::where('ID_Bon', '=', $bon->ID_Bon, 'and')->get();
            foreach ($existingItems as $exItem) {
                if (! in_array($exItem->ID_Item, $submittedItemIds)) {
                    $exItem->ID_Bon = null; // Putuskan hubungan dari bon
                    $exItem->save();

                    // Hapus dari tabel item_lpj juga
                    DB::table('item_lpj')
                        ->where('ID_Bon', $bon->ID_Bon)
                        ->where('Keterangan', $exItem->Keterangan)
                        ->where('Jenis_Pengeluaran', $exItem->Jenis_Pengeluaran)
                        ->where('ID_Sie', $exItem->ID_Sie)
                        ->delete();
                }
            }

            // B. Proses item yang dicentang (Update Realisasi atau Insert Baru)
            foreach ($submittedItemIds as $itemId) {
                $item = Item::findOrFail($itemId);

                // Hubungkan jika ini item baru yang baru dicentang
                if ($item->ID_Bon != $bon->ID_Bon) {
                    $item->ID_Bon = $bon->ID_Bon;
                    $item->save();
                }

                $qty = $request->realisasi_qty[$itemId];
                $harga = $request->realisasi_harga[$itemId];
                $satuan = $request->realisasi_satuan[$itemId];

                $lpjExists = DB::table('item_lpj')
                    ->where('ID_Bon', $bon->ID_Bon)
                    ->where('Keterangan', $item->Keterangan)
                    ->first();

                if ($lpjExists) {
                    // Update yang sudah ada
                    DB::table('item_lpj')
                        ->where('ID_Item_LPJ', $lpjExists->ID_Item_LPJ)
                        ->update([
                            'Qty_Realisasi' => $qty,
                            'Satuan_Realisasi' => $satuan,
                            'Harga_Realisasi' => $harga,
                            'updated_at' => now(),
                        ]);
                } else {
                    // Insert karena baru dicentang
                    DB::table('item_lpj')->insert([
                        'ID_Sie' => $bon->ID_Sie,
                        'ID_Bon' => $bon->ID_Bon,
                        'Jenis_Pengeluaran' => $item->Jenis_Pengeluaran,
                        'Keterangan' => $item->Keterangan,
                        'Qty_Realisasi' => $qty,
                        'Satuan_Realisasi' => $satuan,
                        'Harga_Realisasi' => $harga,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // C. Kelola item baru (langsung dari item_lpj, di luar RAB awal)
            $submittedNewLpjIds = $request->new_lpj_ids ?? [];
            $existingNewLpj = item_lpj::where('ID_Bon', '=', $bon->ID_Bon, 'and')->get()->filter->isNew;

            foreach ($existingNewLpj as $exLpj) {
                if (! in_array($exLpj->ID_Item_LPJ, $submittedNewLpjIds)) {
                    // Hapus item baru jika di-uncheck
                    DB::table('item_lpj')->where('ID_Item_LPJ', $exLpj->ID_Item_LPJ)->delete();
                }
            }

            // Update remaining new items
            foreach ($submittedNewLpjIds as $lpjId) {
                $qty = $request->realisasi_qty_new[$lpjId];
                $harga = $request->realisasi_harga_new[$lpjId];
                $satuan = $request->realisasi_satuan_new[$lpjId];

                DB::table('item_lpj')
                    ->where('ID_Item_LPJ', $lpjId)
                    ->update([
                        'Qty_Realisasi' => $qty,
                        'Satuan_Realisasi' => $satuan,
                        'Harga_Realisasi' => $harga,
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Bon dan realisasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Gagal memperbarui Bon: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bon $bon)
    {
        DB::beginTransaction();
        try {
            // 1. Putuskan hubungan item (kembalikan item ke status "tersedia")
            Item::where('ID_Bon', '=', $bon->ID_Bon, 'and')->update(['ID_Bon' => null]);

            // 2. Hapus catatan realisasinya
            DB::table('item_lpj')->where('ID_Bon', $bon->ID_Bon)->delete();

            // 3. Hapus foto fisiknya
            if ($bon->Foto_Bon && File::exists(public_path('uploads/bon/'.$bon->Foto_Bon))) {
                File::delete(public_path('uploads/bon/'.$bon->Foto_Bon));
            }

            // 4. Hapus Bon
            $bon->delete($bon->ID_Bon);

            DB::commit();

            return redirect()->back()->with('success', 'Bon berhasil dihapus beserta seluruh realisasinya.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Gagal menghapus Bon: '.$e->getMessage());
        }
    }
}