<?php

namespace App\Http\Controllers\Rab;

use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class ItemController extends Controller
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
        $request->validate([
            'id_sie' => 'required|exists:Sie,ID_Sie',
            'Jenis_Pengeluaran' => 'required|string|max:255',
            'Keterangan' => 'required|string|max:255',
            'Qty' => 'required|integer|min:1',
            'Satuan' => 'required|string|max:50',
            'Harga_Unit' => 'required|numeric|min:0',
        ]);

        Item::create([
            'ID_Sie' => $request->id_sie,
            'Jenis_Pengeluaran' => $request->Jenis_Pengeluaran,
            'Keterangan' => $request->Keterangan,
            'Qty' => $request->Qty,
            'Satuan' => $request->Satuan,
            'Harga_Unit' => $request->Harga_Unit,
        ]);

        return redirect()->back()->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'Jenis_Pengeluaran' => 'required|string|max:255',
            'Keterangan' => 'required|string|max:255',
            'Qty' => 'required|integer|min:1',
            'Satuan' => 'required|string|max:50',
            'Harga_Unit' => 'required|numeric|min:0',
        ]);

        $item->update([
            'Jenis_Pengeluaran' => $request->Jenis_Pengeluaran,
            'Keterangan' => $request->Keterangan,
            'Qty' => $request->Qty,
            'Satuan' => $request->Satuan,
            'Harga_Unit' => $request->Harga_Unit,
        ]);

        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete($item->ID_Item);
        return redirect()->back()->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Remove a batch of items (dipilih lewat checkbox) sekaligus.
     */
    public function destroySelected(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'integer|exists:Item,ID_Item',
        ]);

        $count = Item::whereIn('ID_Item', $request->item_ids)->delete();

        return redirect()->back()->with('success', "{$count} item berhasil dihapus.");
    }

    /**
     * Remove semua Item milik sebuah Kegiatan (lintas semua Sie-nya) sekaligus.
     */
    public function destroyAllForKegiatan(Kegiatan $kegiatan)
    {
        $sieIds = $kegiatan->sie()->pluck('ID_Sie');
        $count = Item::whereIn('ID_Sie', $sieIds)->delete();

        return redirect()->back()->with('success', "{$count} item berhasil dihapus sekaligus.");
    }
}
