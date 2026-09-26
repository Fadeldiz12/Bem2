<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
  // ── SEO ──────────────────────────────────────────────────────────────
  // Tiap halaman cukup mengisi section berikut (semuanya opsional):
  //   title            → judul halaman, otomatis ditambah " | BEM Polmed"
  //   meta_title       → judul lengkap (menimpa format di atas, dipakai Beranda)
  //   meta_description → ringkasan untuk hasil pencarian & preview share
  //   canonical        → URL utama halaman (default: URL saat ini tanpa query string)
  //   og_image, og_type → gambar & jenis konten saat link dibagikan
  // Section inline (@section('x', $nilai)) sudah di-escape Blade, jadi di-decode dulu
  // lalu di-escape ulang oleh {{ }} di bawah.
  $seoText   = fn ($v) => trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode((string) $v, ENT_QUOTES | ENT_HTML5, 'UTF-8'))));
  $siteName  = 'BEM Polmed';
  $cabinet   = $year->cabinet_name ?? null;
  $pageTitle = $seoText($__env->yieldContent('title'));
  $fullTitle = $seoText($__env->yieldContent('meta_title'));
  $metaTitle = $fullTitle ?: ($pageTitle !== '' ? $pageTitle.' | '.$siteName : $siteName);
  $ogTitle   = $fullTitle ?: ($pageTitle ?: $siteName);
  $metaDesc  = Str::limit(
      $seoText($__env->yieldContent('meta_description'))
        ?: 'Website resmi BEM Politeknik Negeri Medan (BEM Polmed)'.($cabinet ? ' '.$cabinet : '').': profil, kementerian, pengurus, berita, jadwal peminjaman, dan format surat.',
      160, '...', preserveWords: true
  );
  $canonical = $seoText($__env->yieldContent('canonical')) ?: url()->current();
  $pageImage = $seoText($__env->yieldContent('og_image'));
  $ogImage   = $pageImage ?: (!empty($year->logo_path) ? asset('storage/'.$year->logo_path) : asset('images/logo_bem_polmed.webp'));
  $ogType    = $seoText($__env->yieldContent('og_type')) ?: 'website';
  $robots    = !empty($is_preview) ? 'noindex, nofollow' : 'index, follow, max-image-preview:large';
@endphp
<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDesc }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">
<!-- Open Graph (preview saat link dibagikan di WhatsApp, Facebook, LinkedIn, dll) -->
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $metaDesc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<!-- Twitter/X Card (judul, deskripsi & gambar diambil dari tag og:* di atas; foto besar hanya untuk berita) -->
<meta name="twitter:card" content="{{ $ogType === 'article' && $pageImage ? 'summary_large_image' : 'summary' }}">
@stack('meta')
<!-- Favicon Dinamis dari Logo Kabinet -->
@if(!empty($year->logo_path))
  <link rel="icon" type="image/png" href="{{ asset('storage/' . $year->logo_path) }}">
@else
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%234a1e8a'/><text x='50' y='60' font-size='60' fill='white' text-anchor='middle' font-weight='bold'>B</text></svg>">
@endif
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/responsive-desktop.css') }}" rel="stylesheet">
<style>
:root { --purple-deep:#4a1e8a; --purple:#6d28d9; --purple-light:#8029c6; --purple-pale:#e9d8fd; }
body { font-family:'Poppins',sans-serif; color:#2d1266; background:#fff; }
.font-serif { font-family:'Playfair Display',serif; }
.navbar-custom { background:rgba(255,255,255,.95); backdrop-filter:blur(8px); padding:.8rem 0; box-shadow:0 2px 20px rgba(74,30,138,.08); }
.navbar-custom .navbar-brand { font-family:'Playfair Display',serif; color:var(--purple-deep); font-weight:700; font-size:.88rem; line-height:1.2; }
.navbar-custom .nav-link { color:var(--purple); font-weight:500; font-size:.8rem; padding:.45rem .7rem !important; }
.navbar-custom .nav-link:hover { color:var(--purple-deep); font-weight:700; }
.dropdown-menu { border:none; box-shadow:0 8px 24px rgba(74,30,138,.15); border-radius:10px; font-size:.8rem; }
.dropdown-item { font-size:.8rem; padding:.4rem .9rem; }
.dropdown-item:hover { background:var(--purple-pale); color:var(--purple-deep); }
.page-pill { background:#fff; border-radius:50px; padding:.6rem 1.6rem; box-shadow:0 4px 16px rgba(74,30,138,.1); display:inline-block; color:var(--purple-deep); font-weight:600; font-size:1rem; line-height:1.5; margin:0; }
.btn-purple { background:linear-gradient(135deg,var(--purple-deep),var(--purple-light)); color:#fff; border:none; border-radius:50px; padding:.6rem 1.6rem; font-weight:600; }
.btn-dark-purple { background:#1a0a3e; color:#fff; border-radius:50px; padding:.6rem 1.6rem; font-weight:600; border:none; }
.section-purple { background:linear-gradient(135deg,#9d6fd6,#b794e8); border-radius:24px; color:#fff; padding:2.5rem; }
.footer-purple { background:var(--purple-pale); padding:3rem 0 1.5rem; margin-top:4rem; }
.social-icon { width:38px;height:38px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;color:var(--purple-deep);text-decoration:none; }.whatsapp-float { pointer-events:auto; }
@media (max-width: 767px) {
  .whatsapp-float { display: none !important; }
}.card-soft { border-radius:16px; box-shadow:0 4px 20px rgba(74,30,138,.08); border:none; }
.hero-badge { background:#fff;border-radius:50px;padding:.5rem 1.2rem;font-size:.85rem;color:var(--purple);display:inline-flex;align-items:center;gap:.5rem; }
.org-node { background:#fff;border:2px solid var(--purple);color:var(--purple-deep);border-radius:12px;padding:.6rem 1.2rem;font-weight:600;font-size:.85rem;box-shadow:0 4px 12px rgba(74,30,138,.08);transition:transform .15s;text-decoration:none;display:inline-block; }
.org-node:hover { transform:translateY(-3px);color:var(--purple-deep); }
.org-level-1 { background:var(--purple-deep);color:#fff;border-color:var(--purple-deep); }
.org-level-2 { background:var(--purple);color:#fff;border-color:var(--purple); }
.org-level-4 { font-size:.75rem;padding:.4rem .8rem;border-color:var(--purple-pale); }
.org-line { width:2px;height:24px;background:var(--purple);margin:0 auto; }
.calendar-header-row,.calendar-week-row { display:grid;grid-template-columns:repeat(7,1fr); }
.calendar-header-row { text-align:center;font-weight:600;color:var(--purple);margin-bottom:.5rem; }
.calendar-header-cell { padding:.4rem 0; }
.calendar-day-cell { border:1px solid #eee;border-radius:8px;min-height:90px;padding:.4rem;margin:2px; }
.calendar-day-cell.empty { border:none;background:transparent; }
.calendar-day-num { font-weight:600;text-align:right;font-size:.85rem; }
.calendar-event-badge { font-size:.7rem;border-radius:6px;padding:2px 6px;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#fff; }
.legend-dot { display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px; }

/* ── Login Button ── */
.btn-login {
  background: linear-gradient(135deg, var(--purple-deep), var(--purple-light));
  color: #fff !important;
  border: none;
  border-radius: 50px;
  padding: .45rem 1.2rem;
  font-weight: 600;
  font-size: .85rem;
  transition: opacity .2s, transform .2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: .4rem;
}
.btn-login:hover { opacity: .88; transform: translateY(-1px); color: #fff !important; }

/* ── User Dropdown (sudah login) ── */
.nav-user-btn {
  background: var(--purple-pale);
  color: var(--purple-deep) !important;
  border: 2px solid rgba(74,30,138,.15);
  border-radius: 50px;
  padding: .4rem 1rem;
  font-weight: 600;
  font-size: .85rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  transition: background .2s, border-color .2s;
}
.nav-user-btn:hover,
.nav-user-btn.show { background: var(--purple); color: #fff !important; border-color: var(--purple); }
.nav-user-btn:hover .nav-user-icon,
.nav-user-btn.show .nav-user-icon { color: #fff; }

.nav-user-icon {
  width: 26px; height: 26px;
  border-radius: 50%;
  background: var(--purple);
  color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem;
  flex-shrink: 0;
  transition: background .2s;
}
.nav-user-btn:hover .nav-user-icon { background: rgba(255,255,255,.25); }

.user-dropdown { min-width: 200px; padding: .5rem 0; }
.user-dropdown .dropdown-header {
  font-size: .72rem;
  color: #999;
  text-transform: uppercase;
  letter-spacing: .08em;
  padding: .4rem 1rem;
}
.user-dropdown .dropdown-item {
  font-size: .85rem;
  padding: .5rem 1rem;
  display: flex;
  align-items: center;
  gap: .5rem;
}
.user-dropdown .dropdown-divider { margin: .3rem 0; border-color: rgba(74,30,138,.1); }
.user-dropdown .dropdown-item.text-danger:hover { background: #fff1f2; color: #dc2626 !important; }

@if(isset($is_preview) && $is_preview)
.preview-banner { background:#f59e0b;color:#fff;text-align:center;padding:.5rem;font-size:.85rem;font-weight:600;position:sticky;top:0;z-index:200; }
@endif
</style>
</head>
<body>
@if(isset($is_preview) && $is_preview)
  <div class="preview-banner">⚠️ MODE PREVIEW — Ini tampilan draft, belum dipublish ke publik</div>
@endif
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
      @if(!empty($year->logo_path))
        <img src="{{ asset('storage/' . $year->logo_path) }}" style="height:36px" alt="Logo {{ $year->cabinet_name }}">
      @else
        <span style="font-size:1.8rem">🌺</span>
      @endif
      <span>BEM POLMED {{ $year->year_label ?? '' }}<br><small style="font-size:.7rem;font-weight:600">{{ $year->cabinet_name ?? '' }}</small></span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('profil') }}">Profil</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kementerian</a>
          <ul class="dropdown-menu">
            @foreach($ministries ?? [] as $m)
              <li><a class="dropdown-item" href="{{ route('kementerian.detail', $m->id) }}">{{ $m->alias ?: $m->name }}</a></li>
            @endforeach
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Organisasi</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('pengurus') }}">Pengurus</a></li>
            <li><a class="dropdown-item" href="{{ route('struktur') }}">Struktur Organisasi</a></li>
            <li><a class="dropdown-item" href="{{ route('arsip') }}">Arsip Kabinet</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Layanan Mahasiswa</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('jadwal') }}">Jadwal Peminjaman</a></li>
            <li><a class="dropdown-item" href="{{ route('format-surat') }}">Format Surat</a></li>
            <li><a class="dropdown-item" href="{{ route('rab.index') }}">Sigma Bem</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Hubungi Kami</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('kontak') }}">Kontak</a></li>
            <li><a class="dropdown-item" href="{{ route('media-partner') }}">Media Partner</a></li>
          </ul>
        </li>

        {{-- ── Login / User Button ── --}}
        <li class="nav-item ms-lg-2">
          @if(session('admin_logged_in'))
            <div class="dropdown">
              <button class="nav-user-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="nav-user-icon"><i class="bi bi-person-fill"></i></span>
                {{ session('admin_name') }}
              </button>
              <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                <li>
                  <span class="dropdown-header">
                    {{ session('admin_role') === 'super_admin' ? 'Super Admin' : 'Admin' }}
                  </span>
                </li>

                {{-- Link dashboard hanya untuk super_admin --}}
                @if(session('admin_role') === 'super_admin')
                <li>
                  <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard Admin
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('dashboard') }}">
                    <i class="bi bi-receipt"></i> RGD
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                @endif

                {{-- Link dashboard hanya untuk super_admin --}}
                @if(session('admin_role') === 'admin')
                <li>
                  <a class="dropdown-item" href="{{ route('dashboard') }}">
                    <i class="bi bi-receipt"></i> RGD
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                @endif

                <li>
                  <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                  </a>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('admin.login') }}" class="btn-login">
              <i class="bi bi-person-fill"></i> Login
            </a>
          @endif
        </li>

      </ul>
    </div>
  </div>
</nav>

@yield('content')

<footer class="footer-purple">
  <div class="container">
    <div class="row g-4 align-items-start">
      <div class="col-md-4">
        <div class="d-flex align-items-center gap-2 mb-2">
          @if(!empty($year->logo_path))<img src="{{ asset('storage/' . $year->logo_path) }}" style="height:50px" alt="Logo {{ $year->cabinet_name }}">@endif
          <div>
            <h5 class="font-serif mb-0" style="color:var(--purple-deep)">{{ $year->cabinet_name ?? '' }}</h5>
            <small class="fst-italic text-muted">#{{ str_replace(' ','',$year->tagline ?? '') }}</small>
          </div>
        </div>
      </div>
      <div class="col-md-4 text-center">
        <h6 class="fw-bold mb-2">Sekretariat BEM</h6>
        <p class="small mb-0">{{ $settings['sekretariat_address'] ?? '' }}</p>
      </div>
      <div class="col-md-4 text-center text-md-end">
        <h6 class="fw-bold mb-2">Our Social Media</h6>
        @php
          $formatUrl = function($url) {
              if (empty($url) || $url === '#') return '#';
              return (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) ? $url : 'https://' . $url;
          };
        @endphp
        <div class="d-flex gap-2 justify-content-center justify-content-md-end">
          <a href="{{ $formatUrl($settings['facebook_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="bi bi-facebook"></i></a>
          <a href="{{ $formatUrl($settings['tiktok_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="bi bi-tiktok"></i></a>
          <a href="{{ $formatUrl($settings['youtube_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="bi bi-youtube"></i></a>
          <a href="{{ $formatUrl($settings['twitter_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="bi bi-twitter-x"></i></a>
          <a href="{{ $formatUrl($settings['instagram_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="bi bi-instagram"></i></a>
          <a href="{{ $formatUrl($settings['whatsapp_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
    </div>
    <hr class="mt-4" style="border-color:rgba(74,30,138,.2)">
    <p class="text-center small mb-0">© BEM POLMED {{ date('Y') }} | {{ $year->cabinet_name ?? '' }} Made with 🤍 Kementerian Media & Transformasi Digital</p>
  </div>
</footer>
<a href="{{ $formatUrl($settings['whatsapp_url'] ?? '#') }}" target="_blank" rel="noopener noreferrer" class="position-fixed bottom-0 start-0 m-4 social-icon whatsapp-float" style="background:var(--purple-deep);color:#fff;width:48px;height:48px;font-size:1.3rem;z-index:999;pointer-events:auto;" aria-label="WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>