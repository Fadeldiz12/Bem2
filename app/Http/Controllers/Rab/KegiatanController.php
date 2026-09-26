<?php

namespace App\Http\Controllers\Rab;

use App\Http\Controllers\Controller;

use App\Exports\TabelDanaExport;
use App\Exports\TabelDanaWordExport;
use App\Helpers\LaporanDana;
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
    // Export PDF, Excel & Word (Proposal/RAB dan LPJ)
    // Keduanya memakai format tabel dokumen "Laporan Dana - B. Pengeluaran":
    // LPJ = LaporanDana::pengeluaran(), Proposal = LaporanDana::anggaran()
    // =====================================================================

    public function exportPdfRab(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items'])->findOrFail($id);

        return $this->pdfTabelDana($kegiatan, LaporanDana::anggaran($kegiatan), 'RAB');
    }

    public function exportExcelRab(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items'])->findOrFail($id);

        return Excel::download(new TabelDanaExport(LaporanDana::anggaran($kegiatan)), $this->namaFile('RAB', $kegiatan, 'xlsx'));
    }

    public function exportWordRab(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items'])->findOrFail($id);

        return (new TabelDanaWordExport(LaporanDana::anggaran($kegiatan)))->download($this->namaFile('RAB', $kegiatan, 'docx'));
    }

    public function exportPdf(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj'])->findOrFail($id);

        return $this->pdfTabelDana($kegiatan, LaporanDana::pengeluaran($kegiatan), 'LPJ');
    }

    public function exportExcel(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj'])->findOrFail($id);

        return Excel::download(new TabelDanaExport(LaporanDana::pengeluaran($kegiatan)), $this->namaFile('LPJ', $kegiatan, 'xlsx'));
    }

    public function exportWord(int $id)
    {
        $kegiatan = Kegiatan::with(['sie.items', 'sie.item_lpj'])->findOrFail($id);

        return (new TabelDanaWordExport(LaporanDana::pengeluaran($kegiatan)))->download($this->namaFile('LPJ', $kegiatan, 'docx'));
    }

    private function pdfTabelDana(Kegiatan $kegiatan, array $laporan, string $jenis)
    {
        return Pdf::loadView('rab.export.tabel-dana-pdf', compact('kegiatan', 'laporan'))
            ->setPaper('A4', 'portrait')
            ->setCallbacks(LaporanDana::pdfCallbacks())
            ->download($this->namaFile($jenis, $kegiatan, 'pdf'));
    }

    private function namaFile(string $jenis, Kegiatan $kegiatan, string $ekstensi): string
    {
        return $jenis.'_'.str_replace(' ', '_', $kegiatan->Nama_Kegiatan).'.'.$ekstensi;
    }
}