<?php

namespace Tests\Feature;

use App\Models\{ManagementYear, Ministry, Post, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    private ManagementYear $year;
    private Ministry $ministry;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->year = ManagementYear::create([
            'year_label'   => '2025/2026',
            'cabinet_name' => 'Kabinet Karsa Abhinaya',
            'logo_path'    => 'uploads/logos/kabinet.webp',
            'visi'         => 'Menjadikan BEM POLMED responsif dan kolaboratif.',
            'misi'         => '[]',
            'status'       => 'published',
            'is_active'    => true,
            'start_date'   => '2025-08-01',
            'end_date'     => '2026-07-31',
        ]);

        $this->ministry = Ministry::create([
            'management_year_id' => $this->year->id,
            'name'               => 'Media dan Transformasi Digital',
            'description'        => 'Kementerian yang mengelola website dan media sosial BEM.',
        ]);

        $author = User::create([
            'name'          => 'Admin',
            'email'         => 'admin@example.com',
            'password_hash' => bcrypt('secret'),
            'role'          => 'super_admin',
        ]);

        $this->post = Post::create([
            'author_id'      => $author->id,
            'title'          => 'Pelantikan Pengurus & Rapat Kerja',
            'slug'           => 'pelantikan-pengurus',
            'content'        => "Paragraf pertama berita.\n\nParagraf kedua berita.",
            'featured_image' => 'uploads/posts/pelantikan.webp',
            'status'         => 'published',
            'category'       => 'kegiatan',
            'published_at'   => now(),
        ]);
    }

    public function test_home_has_brand_title_description_canonical_and_open_graph(): void
    {
        $res = $this->get('/')->assertOk();

        $res->assertSee('<title>BEM Polmed | Badan Eksekutif Mahasiswa Politeknik Negeri Medan</title>', false);
        $res->assertSee('<meta name="description" content="Website resmi BEM Politeknik Negeri Medan (BEM Polmed) Kabinet Karsa Abhinaya', false);
        $res->assertSee('<link rel="canonical" href="'.url('/').'">', false);
        $res->assertSee('<meta property="og:image" content="'.asset('storage/uploads/logos/kabinet.webp').'">', false);
        $res->assertSee('<meta name="twitter:card" content="summary">', false);
        $res->assertSee('<meta name="robots" content="index, follow, max-image-preview:large">', false);
    }

    public function test_every_public_page_has_one_h1_description_brand_title_and_image_alts(): void
    {
        $pages = [
            '/', '/profil', '/pengurus', '/struktur-organisasi', '/arsip-kabinet',
            '/layanan/jadwal-peminjaman', '/layanan/format-surat', '/hubungi/kontak',
            '/hubungi/media-partner', '/berita', '/berita/pelantikan-pengurus',
            '/kementerian/'.$this->ministry->id, '/rab',
        ];

        foreach ($pages as $page) {
            $html = $this->get($page)->assertOk()->getContent();

            $this->assertSame(1, preg_match_all('/<h1\b/', $html), "Halaman $page harus punya tepat satu <h1>");
            $this->assertMatchesRegularExpression('/<meta name="description" content="[^"]{20,}">/', $html, "Halaman $page belum punya meta description");
            $this->assertMatchesRegularExpression('/<title>[^<]*BEM Polmed[^<]*<\/title>/', $html, "Judul $page belum memuat BEM Polmed");
            $this->assertDoesNotMatchRegularExpression('/<img\b(?:(?!\balt=)[^>])*>/', $html, "Halaman $page masih punya <img> tanpa alt");
        }
    }

    public function test_news_detail_is_shared_as_article_with_its_own_image(): void
    {
        $res = $this->get('/berita/pelantikan-pengurus')->assertOk();

        $res->assertSee('<title>Pelantikan Pengurus &amp; Rapat Kerja | BEM Polmed</title>', false);
        $res->assertSee('<meta name="description" content="Paragraf pertama berita. Paragraf kedua berita.">', false);
        $res->assertSee('<meta property="og:type" content="article">', false);
        $res->assertSee('<meta property="og:title" content="Pelantikan Pengurus &amp; Rapat Kerja">', false);
        $res->assertSee('<meta property="og:image" content="'.asset('storage/uploads/posts/pelantikan.webp').'">', false);
        $res->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
        $res->assertSee('<meta property="article:published_time"', false);
    }

    public function test_news_list_canonical_keeps_only_category_and_page(): void
    {
        $this->get('/berita?kategori=kegiatan&utm_source=wa')
            ->assertSee('<link rel="canonical" href="'.url('/berita?kategori=kegiatan').'">', false)
            ->assertSee('<title>Berita &amp; Pengumuman – Kategori Kegiatan | BEM Polmed</title>', false);

        $this->get('/berita?fbclid=abc')
            ->assertSee('<link rel="canonical" href="'.url('/berita').'">', false);
    }

    public function test_sitemap_lists_public_pages_news_and_ministries(): void
    {
        $res = $this->get('/sitemap.xml')->assertOk();

        $this->assertStringStartsWith('application/xml', $res->headers->get('Content-Type'));
        $xml = simplexml_load_string($res->getContent());
        $this->assertNotFalse($xml, 'sitemap.xml harus XML yang valid');

        $locs = [];
        foreach ($xml->url as $url) {
            $locs[] = (string) $url->loc;
        }

        $this->assertContains(url('/'), $locs);
        $this->assertContains(url('/berita/pelantikan-pengurus'), $locs);
        $this->assertContains(url('/kementerian/'.$this->ministry->id), $locs);
        $this->assertNotContains(url('/login'), $locs);
    }

    public function test_robots_txt_points_to_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false);
    }

    public function test_private_pages_are_noindex_and_public_pages_are_not(): void
    {
        $this->get('/login')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/admin/forgot-password')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/admin/dashboard')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/proposal')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/api/activities')->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/')->assertHeaderMissing('X-Robots-Tag');
        $this->get('/berita')->assertHeaderMissing('X-Robots-Tag');
    }
}
