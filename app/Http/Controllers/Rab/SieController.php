<?php

namespace App\Http\Controllers\Rab;

use App\Http\Controllers\Controller;

use App\Models\Sie;
use Illuminate\Http\Request;

class SieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

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
            'kegiatan_id' => 'required|exists:Kegiatan,ID_Kegiatan',
            'nama_Sie' => 'required|array', // Ubah menjadi array
            'nama_Sie.*' => 'required|string|max:255',
        ]);

        foreach ($request->nama_Sie as $namaSie) {
            try {
                Sie::create([
                    'ID_Kegiatan' => $request->kegiatan_id,
                    'Nama_Sie' => $namaSie,
                ]);
            } catch (\Exception $e) {
                // Jika ingin melihat error langsung di layar, gunakan dd()
                // Pastikan ada titik komanya!
                dd($request->all(), $e->getMessage());

                return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan Sie: '.$e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Sie berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sie $sie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sie $sie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sie $sie)
    {
        $request->validate([
            'Nama_Sie' => 'required|string|max:255',
        ]);

        $sie->Nama_Sie = $request->Nama_Sie;
        $sie->save();

        return redirect()->back()->with('success', 'Sie berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sie $sie)
    {
        $sie->delete($sie->ID_Sie);

        return redirect()->back()->with('success', 'Sie berhasil dihapus.');
    }
}
