<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManagementYearRequest;
use App\Helpers\ImageHelper;
use App\Models\{ManagementYear, Ministry, SiteSetting};

class ManagementYearController extends Controller
{
    public function index()
    {
        return view('admin.management_years.index', ['years' => ManagementYear::latest()->get()]);
    }

    public function create()
    {
        return view('admin.management_years.form', ['year' => null, 'misi_text' => '']);
    }

    public function store(ManagementYearRequest $request)
    {
        $data             = $this->buildData($request);
        $data['status']   = 'draft';
        $data['is_active']= false;
        ManagementYear::create($data);
        return redirect()->route('admin.kabinet.index')->with('success', 'Kabinet berhasil dibuat sebagai Draft.');
    }

    public function edit(int $id)
    {
        $year = ManagementYear::findOrFail($id);
        return view('admin.management_years.form', [
            'year'      => $year,
            'misi_text' => implode("\n", $year->misi_list),
        ]);
    }

    public function update(ManagementYearRequest $request, int $id)
    {
        $year = ManagementYear::findOrFail($id);
        $year->update($this->buildData($request, $year));
        return redirect()->route('admin.kabinet.index')->with('success', 'Berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $year = ManagementYear::findOrFail($id);
        if ($year->status === 'published') {
            return back()->with('error', 'Kabinet aktif tidak dapat dihapus.');
        }
        $year->delete();
        return redirect()->route('admin.kabinet.index')->with('success', 'Berhasil dihapus.');
    }

    public function publish(int $id)
    {
        ManagementYear::publish($id);
        return redirect()->route('admin.kabinet.index')->with('success', 'Kabinet berhasil dipublish.');
    }

    public function preview(int $id)
    {
        $year       = ManagementYear::findOrFail($id);
        $ministries = Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get();
        $settings   = SiteSetting::allAsArray();
        $misi_list  = $year->misi_list;
        return view('public.home.index', compact('year','ministries','settings','misi_list') + [
            'recent_posts' => collect(), 'upcoming_activities' => collect(), 'is_preview' => true,
        ]);
    }

    private function buildData(ManagementYearRequest $request, ?ManagementYear $existing = null): array
    {
        $data = $request->safe()->except([
            'logo_path','presma_photo','wapresma_photo','misi','filosofi','warna'
        ]);

        $data['misi'] = json_encode(
            array_values(array_filter(array_map('trim', explode("\n", (string) $request->misi))))
        );

        // Logo kabinet disimpan langsung tanpa compress — menjaga kualitas
        // Foto presma & wapresma tetap dicompress karena foto orang
        foreach (['logo_path', 'presma_photo', 'wapresma_photo'] as $field) {
            if ($request->hasFile($field)) {
                if ($field === 'logo_path') {
                    // Logo kabinet: simpan langsung tanpa compress
                    $data[$field] = $request->file($field)->store('uploads/logos', 'public');
                } else {
                    // Foto presma & wapresma: compress ke WebP
                    $data[$field] = ImageHelper::compressAndStore(
                        $request->file($field), 'uploads/logos', 600, 88
                    );
                }
            }
        }

        // Filosofi logo: tiap elemen bisa punya gambar
        $filosofiRaw      = $request->input('filosofi', []);
        $filosofiFiles    = $request->file('filosofi', []);
        $existingFilosofi = $existing ? $existing->filosofi_list : [];
        $filosofiResult   = [];
        foreach ($filosofiRaw as $i => $f) {
            if (empty($f['nama'])) continue;
            $item = [
                'nama'       => $f['nama'],
                'penjelasan' => $f['penjelasan'] ?? '',
                'gambar'     => $existingFilosofi[$i]['gambar'] ?? null,
            ];
            if (isset($filosofiFiles[$i]['gambar']) && $filosofiFiles[$i]['gambar']->isValid()) {
                $item['gambar'] = ImageHelper::compressAndStore(
                    $filosofiFiles[$i]['gambar'], 'uploads/logos', 200, 88
                );
            }
            $filosofiResult[] = $item;
        }
        $data['filosofi_logo'] = json_encode($filosofiResult);

        // Makna warna
        $warnaResult = [];
        foreach ($request->input('warna', []) as $w) {
            if (empty($w['nama'])) continue;
            $warnaResult[] = ['nama' => $w['nama'], 'hex' => $w['hex'] ?? '', 'makna' => $w['makna'] ?? ''];
        }
        $data['makna_warna'] = json_encode($warnaResult);

        return $data;
    }
}