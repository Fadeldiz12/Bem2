<?php
namespace App\Http\Controllers;

use App\Models\{ManagementYear, Ministry, Post};
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SeoController extends Controller
{
    /**
     * sitemap.xml — daftar semua halaman publik agar cepat ditemukan
     * dan diindeks mesin pencari (Google Search Console, Bing, dll).
     */
    public function sitemap(): Response
    {
        $urls = [];
        $add  = function (string $loc, $lastmod = null, string $changefreq = 'monthly', string $priority = '0.5') use (&$urls) {
            $urls[] = [
                'loc'        => $loc,
                'lastmod'    => $lastmod?->toAtomString(),
                'changefreq' => $changefreq,
                'priority'   => $priority,
            ];
        };

        $latestPost = Post::where('status', 'published')->max('updated_at');
        $latestPost = $latestPost ? Carbon::parse($latestPost) : null;

        // Halaman statis
        $add(route('home'),           $latestPost, 'daily',   '1.0');
        $add(route('berita'),         $latestPost, 'daily',   '0.9');
        $add(route('profil'),         null,        'monthly', '0.8');
        $add(route('pengurus'),       null,        'monthly', '0.7');
        $add(route('struktur'),       null,        'monthly', '0.7');
        $add(route('jadwal'),         null,        'weekly',  '0.6');
        $add(route('format-surat'),   null,        'monthly', '0.6');
        $add(route('kontak'),         null,        'yearly',  '0.5');
        $add(route('media-partner'),  null,        'yearly',  '0.5');
        $add(route('arsip'),          null,        'yearly',  '0.4');
        $add(route('rab.index'),      null,        'yearly',  '0.4');

        // Kementerian kabinet aktif
        $year = ManagementYear::getActive();
        if ($year) {
            Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get()
                ->each(fn ($m) => $add(route('kementerian.detail', $m->id), $m->updated_at, 'monthly', '0.7'));
        }

        // Arsip kabinet
        ManagementYear::where('status', 'archived')->orderBy('start_date', 'desc')->get()
            ->each(fn ($y) => $add(route('arsip.detail', $y->id), $y->updated_at, 'yearly', '0.3'));

        // Berita yang sudah terbit
        Post::where('status', 'published')->latest('published_at')->get(['slug', 'updated_at'])
            ->each(fn ($p) => $add(route('berita.detail', $p->slug), $p->updated_at, 'monthly', '0.8'));

        return response()
            ->view('seo.sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * robots.txt dinamis supaya baris "Sitemap:" selalu memakai domain yang sedang dipakai.
     */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow:',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
