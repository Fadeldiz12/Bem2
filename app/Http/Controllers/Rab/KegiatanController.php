<?php

namespace App\Http\Controllers\Rab;

use App\Http\Controllers\Controller;

use App\Exports\LpjExport;
use App\Exports\LpjWordExport;
use App\Helpers\LaporanDana;
use App\Exports\RabExport;
use App\Exports\RabWordExport;
use App\Models\Kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = session('admin_id');

        $kegiatan = Kegiatan::where('user_id', '=', $user)->get();

        $site = $request->segment(1);
        if ($site === 'lpj') {
            return view('rab.lpj.index', compact('kegiatan'));
        }
        return view('rab.proposal.index', compact('kegiatan'));
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
        $user = session('admin_id');

        $request->validate([
            'Nama_Kegiatan' => 'required|string|max:255',
            'Tanggal_Pelaksanaan' => 'required|date',
            'Jenis_RAB' => 'required|string|max:255',
        ]);

        $kegiatan = new Kegiatan();
        $kegiatan->Nama_Kegiatan = $request->Nama_Kegiatan;
        $kegiatan->Tanggal_Pelaksanaan = $request->Tanggal_Pelaksanaan;
        $kegiatan->Jenis_RAB = $request->Jenis_RAB;
        $kegiatan->user_id = $user;
        $kegiatan->save();

        return redirect()->route('proposal.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, Request $request)
    {
        $kegiatan = Kegiatan::with(['sie.items', 'items'])->findOrFail($id);
        $sie = $kegiatan->sie;

        $site = $request->segment(1);
        if ($site === 'lpj') {
            // Reload dengan eager load sie.item_lpj.bon (dipakai untuk kalkulasi
            // realisasi, dan untuk menyaring baris item_lpj yang Bon-nya sudah
            // tidak ada lagi di database / data nyasar).
            $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj.bon', 'sie.bons'])->findOrFail($id);
            $sie = $kegiatan->sie;

            return view('rab.lpj.show', compact('kegiatan', 'sie'));
        }
        return view('rab.proposal.show', compact('kegiatan', 'sie'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        $kegiatan = Kegiatan::findOrFail($kegiatan->ID_Kegiatan);
        return view('rab.proposal.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'Nama_Kegiatan' => 'required|string|max:255',
            'Tanggal_Pelaksanaan' => 'required|date',
            'Jenis_RAB' => 'required|string|max:255',
        ]);

        $kegiatan->Nama_Kegiatan = $request->Nama_Kegiatan;
        $kegiatan->Tanggal_Pelaksanaan = $request->Tanggal_Pelaksanaan;
        $kegiatan->Jenis_RAB = $request->Jenis_RAB;

        $kegiatan->save();

        return redirect()->route('proposal.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete($kegiatan->ID_Kegiatan);
        return redirect()->route('proposal.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    // =====================================================================
    // Export PDF & Excel (Proposal/RAB dan LPJ)
    // Butuh package: barryvdh/laravel-dompdf, maatwebsite/excel
    // =====================================================================

    public function exportPdfRab(int $id)
    {
        // Hanya perlu relasi ke sie dan items (RAB)
        $kegiatan = Kegiatan::with(['sie.items'])->findOrFail($id);
        $sies = $kegiatan->sie;

        // Menggunakan ukuran kertas portrait/A4 karena kolomnya tidak terlalu banyak
        $pdf = Pdf::loadView('rab.proposal.export-pdf', compact('kegiatan', 'sies'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('RAB_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.pdf');
    }

    public function exportExcelRab(int $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        return Excel::download(new RabExport($id), 'RAB_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.xlsx');
    }

    public function exportWordRab(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items'])->findOrFail($id);

        return (new RabWordExport($kegiatan))
            ->download('RAB_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.docx');
    }

    public function exportPdf(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj'])->findOrFail($id);
        $laporan = LaporanDana::pengeluaran($kegiatan);

        // Format mengikuti dokumen "XII. Laporan Dana - B. Pengeluaran" (A4 portrait)
        $pdf = Pdf::loadView('rab.lpj.export-pdf', compact('kegiatan', 'laporan'))
            ->setPaper('A4', 'portrait')
            ->setCallbacks(LaporanDana::pdfCallbacks());

        return $pdf->download('LPJ_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.pdf');
    }

    public function exportExcel(int $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $lpjexport = new LpjExport($id);

        return Excel::download($lpjexport, 'LPJ_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.xlsx');
    }

    public function exportWord(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj'])->findOrFail($id);

        return (new LpjWordExport(LaporanDana::pengeluaran($kegiatan)))
            ->download('LPJ_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.docx');
    }
}