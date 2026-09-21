<?php
namespace App\Http\Controllers;

use App\Models\{ManagementYear, Ministry, Post, Activity, LetterFormat, MediaPartner, SiteSetting, Member, Department};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    private function baseData(): array
    {
        $year       = ManagementYear::getActive();
        $ministries = $year ? Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get() : collect();
        $settings   = SiteSetting::allAsArray();
        $misi_list  = $year ? $year->misi_list : [];
        return compact('year', 'ministries', 'settings', 'misi_list');
    }

    public function index()
    {
        $data = $this->baseData();
        $data['recent_posts']        = Post::where('status','published')->with('author')->latest('published_at')->take(6)->get();
        $data['upcoming_activities'] = Activity::where('status','disetujui')->where('activity_date','>=',now())->orderBy('activity_date')->take(3)->get();
        return view('public.home.index', $data);
    }

    public function profil()
    {
        $data = $this->baseData();
        $year = $data['year'];

        $menteriList = [];
        if ($year) {
            foreach ($data['ministries'] as $m) {
                $menteri = Member::where('ministry_id', $m->id)->where('role', 'menteri')->first();
                if ($menteri) {
                    $menteri->ministry_name  = $m->name;
                    $menteri->ministry_alias = $m->alias ?: $m->name;
                    $menteri->ministry_logo  = $m->logo_path;
                    $menteri->ministry_id    = $m->id;
                    $menteriList[] = $menteri;
                }
            }
        }

        $data['presma']       = $year?->presma_name   ? (object)['full_name' => $year->presma_name,   'photo_path' => $year->presma_photo,   'position' => 'Presiden Mahasiswa',        'ministry_name' => 'BEM Polmed', 'ministry_alias' => 'Presma',   'ministry_logo' => $year->logo_path] : null;
        $data['wapresma']     = $year?->wapresma_name ? (object)['full_name' => $year->wapresma_name, 'photo_path' => $year->wapresma_photo, 'position' => 'Wakil Presiden Mahasiswa',  'ministry_name' => 'BEM Polmed', 'ministry_alias' => 'Wapresma', 'ministry_logo' => $year->logo_path] : null;
        $data['menteri_list'] = $menteriList;
        $data['filosofi_list']= $year ? $year->filosofi_list : [];
        $data['warna_list']   = $year ? $year->warna_list : [];

        return view('public.home.profil', $data);
    }

    public function kementerianDetail(int $id)
    {
        $data     = $this->baseData();
        $ministry = Ministry::with(['menteri','departments.head','departments.staff','departments.programs'])->findOrFail($id);
        return view('public.ministry.detail', array_merge($data, ['ministry' => $ministry]));
    }

    public function pengurus()
    {
        $data    = $this->baseData();
        $year    = $data['year'];
        $pengurus = $year
            ? Member::with(['ministry', 'department'])
                ->where('management_year_id', $year->id)
                ->orderBy('sort_order')
                ->get()
            : collect();

        return view('public.home.pengurus', array_merge($data, ['pengurus' => $pengurus]));
    }

    public function strukturOrganisasi()
    {
        $data = $this->baseData();
        $data['ministries_with_depts'] = $data['year']
            ? Ministry::with('departments')->where('management_year_id', $data['year']->id)->orderBy('sort_order')->get()
            : collect();
        return view('public.home.struktur_organisasi', $data);
    }

    public function arsipKabinet()
    {
        $data = $this->baseData();
        $data['archived_years'] = ManagementYear::where('status','archived')->orderBy('start_date','desc')->get();
        return view('public.home.arsip_kabinet', $data);
    }

    public function arsipKabinetDetail(int $id)
    {
        $data         = $this->baseData();
        $archivedYear = ManagementYear::where('id',$id)->where('status','archived')->firstOrFail();

        $data['archived_year']       = $archivedYear;
        $data['archived_ministries'] = Ministry::with(['members.department'])
            ->where('management_year_id', $id)
            ->orderBy('sort_order')
            ->get();
        $data['archived_misi']       = $archivedYear->misi_list;

        return view('public.home.arsip_kabinet_detail', $data);
    }

    public function jadwal()    { return view('public.services.jadwal',    $this->baseData()); }

    public function formatSurat()
    {
        $data = $this->baseData();
        $data['formats'] = LetterFormat::where('is_active',true)->orderBy('sort_order')->get();
        return view('public.services.format_surat', $data);
    }

    public function downloadSurat(int $id, string $type = 'hmps')
    {
        $format = LetterFormat::findOrFail($id);
        if (!$format->is_active) return redirect()->route('format-surat');

        $type = strtolower($type);
        if ($type === 'ukm' && $format->file_path_ukm) {
            $filePath = $format->file_path_ukm;
        } elseif ($type === 'hmps' && $format->file_path_hmps) {
            $filePath = $format->file_path_hmps;
        } elseif ($format->file_path_hmps) {
            $filePath = $format->file_path_hmps;
        } elseif ($format->file_path_ukm) {
            $filePath = $format->file_path_ukm;
        } else {
            $filePath = $format->file_path;
        }

        if (!$filePath) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $filePath = $this->normalizePublicFilePath($filePath);
        $disk = Storage::disk('public');
        if (!$disk->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $format->increment('download_count');
        return $disk->download($filePath, basename($filePath));
    }

    private function normalizePublicFilePath(string $filePath): string
    {
        $filePath = str_replace('\\', '/', $filePath);
        $filePath = trim($filePath, '/');

        $prefixes = [
            'storage/app/public/',
            'storage/app/',
            'public/storage/',
            'storage/',
            'public/',
        ];

        foreach ($prefixes as $prefix) {
            if (str_starts_with($filePath, $prefix)) {
                $filePath = substr($filePath, strlen($prefix));
                break;
            }
        }

        return ltrim($filePath, '/');
    }

    public function kontak()
    {
        $data = $this->baseData();
        $data['contacts'] = $data['year']
            ? Ministry::where('management_year_id',$data['year']->id)->whereNotNull('whatsapp_number')->get()
            : collect();
        return view('public.contact.kontak', $data);
    }

    public function mediaPartner()
    {
        $data = $this->baseData();
        $data['partners'] = MediaPartner::all();
        return view('public.contact.media_partner', $data);
    }

    public function berita(Request $request)
    {
        $data     = $this->baseData();
        $category = $request->get('kategori','');
        $query    = Post::where('status','published')->with('author')->latest('published_at');
        if ($category) $query->where('category', $category);
        $data['posts']    = $query->paginate(12);
        $data['category'] = $category;
        return view('public.home.berita', $data);
    }

    public function beritaDetail(string $slug)
    {
        $data = $this->baseData();
        $post = Post::where('slug',$slug)->where('status','published')->with('author')->firstOrFail();
        return view('public.home.berita_detail', array_merge($data, ['post' => $post]));
    }
}