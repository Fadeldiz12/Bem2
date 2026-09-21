<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>@yield('title','Admin') - BEM Polmed</title>
<!-- Favicon Dinamis dari Logo Kabinet -->
@if(!empty($year->logo_path))
  <link rel="icon" type="image/png" href="{{ asset('storage/' . $year->logo_path) }}">
@else
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%234a1e8a'/><text x='50' y='60' font-size='60' fill='white' text-anchor='middle' font-weight='bold'>A</text></svg>">
@endif
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
  --purple: #4a1e8a;
  --purple-light: #7c3aed;
  --sidebar-w: 260px;
}

/* ════════════════════════════════════════════
   BASE
════════════════════════════════════════════ */
html, body {
  height: 100%;
  /* JANGAN ada overflow:hidden di sini agar sidebar bisa scroll */
}
body { background: #f8f9fa; overflow-x: hidden; }

/* ════════════════════════════════════════════
   SIDEBAR
════════════════════════════════════════════ */
.sidebar {
  width: var(--sidebar-w);
  height: 100dvh;          /* tinggi eksplisit agar overflow-y bekerja */
  background: linear-gradient(180deg, #1a0a3e, #2d1266);
  position: fixed;
  top: 0; left: 0;
  z-index: 1040;
  overflow-y: scroll;     /* scroll (bukan auto) agar selalu bisa di-scroll */
  overflow-x: hidden;
  transition: transform .3s ease;

  /* Scrollbar halus — Firefox */
  scrollbar-width: thin;
  scrollbar-color: rgba(255,255,255,.15) transparent;
}

/* Scrollbar — Chrome / Edge / Safari */
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-track { background: transparent; }
.sidebar::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,.2);
  border-radius: 4px;
}
.sidebar::-webkit-scrollbar-thumb:hover {
  background: rgba(255,255,255,.35);
}

/* Sembunyikan sidebar di layar kecil secara default */
@media (max-width: 1023px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.sidebar-open { transform: translateX(0); }
}

/* ── Brand ── */
.sidebar-brand {
  padding: 1.25rem 1.25rem 1rem;
  border-bottom: 1px solid rgba(255,255,255,.1);
  display: flex;
  align-items: center;
  gap: .75rem;
  position: sticky;   /* brand tetap terlihat saat nav di-scroll */
  top: 0;
  z-index: 1;
  background: linear-gradient(180deg, #1a0a3e, #2d1266); /* sama dgn sidebar */
}
.sidebar-brand-text h6 { color: #fff; font-weight: 700; margin: 0; font-size: .88rem; }
.sidebar-brand-text small { color: rgba(255,255,255,.45); font-size: .72rem; }

/* Tombol close sidebar — hanya tampil di mobile */
.sidebar-close {
  display: none;
  margin-left: auto;
  background: none;
  border: none;
  color: rgba(255,255,255,.5);
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}
@media (max-width: 1023px) {
  .sidebar-close { display: flex; align-items: center; }
}

/* ── Section label ── */
.sidebar-section {
  padding: .9rem 1.25rem .2rem;
  font-size: .68rem;
  color: rgba(255,255,255,.35);
  text-transform: uppercase;
  letter-spacing: 1.2px;
  font-weight: 600;
}

/* ── Nav links ── */
.sidebar .nav-link {
  color: rgba(255,255,255,.68);
  padding: .55rem 1.1rem;
  font-size: .85rem;
  display: flex;
  align-items: center;
  gap: .55rem;
  border-radius: 8px;
  margin: 2px .6rem;
  transition: background .2s, color .2s;
  white-space: nowrap;
}
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  background: rgba(124,58,237,.4);
  color: #fff;
}
.sidebar .nav-link i { font-size: .95rem; width: 18px; flex-shrink: 0; }

/* Nav wrapper — padding bawah agar item terakhir tidak terpotong */
.sidebar nav { padding-bottom: calc(2rem + env(safe-area-inset-bottom)); }

/* ════════════════════════════════════════════
   RESPONSIF BERDASARKAN TINGGI LAYAR (LAPTOP)
   Kurangi padding & font saat viewport pendek
════════════════════════════════════════════ */
@media (max-height: 800px) {
  .sidebar-brand { padding: 1rem 1.25rem .85rem; }
  .sidebar-section { padding: .7rem 1.25rem .15rem; font-size: .66rem; }
  .sidebar .nav-link { padding: .45rem 1rem; font-size: .83rem; }
  .sidebar nav { padding-bottom: 1.5rem; }
}

@media (max-height: 700px) {
  .sidebar-brand { padding: .85rem 1.25rem .7rem; }
  .sidebar-section { padding: .55rem 1.25rem .1rem; font-size: .64rem; }
  .sidebar .nav-link { padding: .35rem .9rem; font-size: .81rem; }
  .sidebar nav { padding-bottom: 1.25rem; }
}

@media (max-height: 600px) {
  .sidebar-brand { padding: .7rem 1rem .6rem; }
  .sidebar-section { padding: .4rem 1rem .05rem; font-size: .62rem; letter-spacing: 1px; }
  .sidebar .nav-link { padding: .28rem .85rem; font-size: .79rem; margin: 1px .5rem; border-radius: 6px; }
  .sidebar .nav-link i { font-size: .88rem; }
  .sidebar nav { padding-bottom: 1rem; }
}

@media (max-height: 500px) {
  .sidebar-brand { padding: .55rem 1rem .5rem; }
  .sidebar-section { padding: .3rem 1rem .05rem; font-size: .6rem; }
  .sidebar .nav-link { padding: .22rem .8rem; font-size: .77rem; margin: 1px .4rem; }
  .sidebar nav { padding-bottom: .75rem; }
}

/* ════════════════════════════════════════════
   OVERLAY (klik di luar tutup sidebar)
════════════════════════════════════════════ */
.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 1039;
}
.sidebar-overlay.active { display: block; }

/* ════════════════════════════════════════════
   TOPBAR
════════════════════════════════════════════ */
.topbar {
  margin-left: var(--sidebar-w);
  background: #fff;
  padding: .85rem 1.5rem;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 99;
  transition: margin-left .3s ease;
}

@media (max-width: 1023px) {
  .topbar { margin-left: 0; }
}

/* Hamburger — hanya tampil di layar kecil */
.sidebar-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 1.4rem;
  color: var(--purple);
  cursor: pointer;
  padding: 0;
  line-height: 1;
  margin-right: .75rem;
}
@media (max-width: 1023px) {
  .sidebar-toggle { display: flex; align-items: center; }
}

.topbar-left { display: flex; align-items: center; }
.topbar-title { font-size: .95rem; font-weight: 600; color: #1a1a2e; margin: 0; }

.topbar-right {
  display: flex;
  align-items: center;
  gap: .6rem;
  flex-shrink: 0;
}
.topbar-user {
  display: flex;
  align-items: center;
  gap: .5rem;
}
.topbar-user-name {
  font-size: .82rem;
  color: #555;
  white-space: nowrap;
}
/* Sembunyikan nama di layar sangat kecil */
@media (max-width: 480px) {
  .topbar-user-name { display: none; }
}
.topbar-badge {
  font-size: .68rem;
  padding: .25rem .6rem;
  border-radius: 50px;
  background: var(--purple-light);
  color: #fff;
  font-weight: 600;
  white-space: nowrap;
}

/* ════════════════════════════════════════════
   MAIN CONTENT
════════════════════════════════════════════ */
.main-content {
  margin-left: var(--sidebar-w);
  padding: 1.5rem;
  min-height: calc(100vh - 57px);
  transition: margin-left .3s ease;
}

@media (max-width: 1023px) {
  .main-content { margin-left: 0; padding: 1rem; }
}
@media (max-width: 576px) {
  .main-content { padding: .75rem; }
}

/* ════════════════════════════════════════════
   CARDS & COMPONENTS
════════════════════════════════════════════ */
.card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.card-header {
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
  font-weight: 600;
  padding: .9rem 1.25rem;
  border-radius: 12px 12px 0 0 !important;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: .5rem;
}
.btn-primary { background: var(--purple-light); border-color: var(--purple-light); }
.btn-primary:hover { background: var(--purple); border-color: var(--purple); }

.stat-card {
  background: linear-gradient(135deg, var(--purple), var(--purple-light));
  color: white;
  border-radius: 12px;
  padding: 1.25rem;
}

.alert { border-radius: 10px; }
</style>
</head>
<body>

{{-- ── Overlay (klik di luar untuk tutup sidebar) ── --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ --}}
<div class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <span style="font-size:1.4rem">🏛️</span>
    <div class="sidebar-brand-text">
      <h6>BEM Polmed</h6>
      <small>Admin Panel</small>
    </div>
    {{-- Tombol close hanya muncul di mobile --}}
    <button class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>

  <nav class="pt-1">
    <div class="sidebar-section">Utama</div>
    <a href="{{ route('admin.dashboard') }}"
       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-section">Organisasi</div>
    <a href="{{ route('admin.kabinet.index') }}"
       class="nav-link {{ request()->routeIs('admin.kabinet.*') ? 'active' : '' }}">
      <i class="bi bi-archive"></i> Manajemen Kabinet
    </a>
    <a href="{{ route('admin.kementerian.index') }}"
       class="nav-link {{ request()->routeIs('admin.kementerian.*') ? 'active' : '' }}">
      <i class="bi bi-building"></i> Kementerian
    </a>
    <a href="{{ route('admin.departemen.index') }}"
       class="nav-link {{ request()->routeIs('admin.departemen.*') ? 'active' : '' }}">
      <i class="bi bi-diagram-3"></i> Departemen
    </a>
    <a href="{{ route('admin.pengurus.index') }}"
       class="nav-link {{ request()->routeIs('admin.pengurus.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> Pengurus
    </a>
    <a href="{{ route('admin.program.index') }}"
       class="nav-link {{ request()->routeIs('admin.program.*') ? 'active' : '' }}">
      <i class="bi bi-kanban"></i> Program Kerja
    </a>

    <div class="sidebar-section">Konten</div>
    <a href="{{ route('admin.berita.index') }}"
       class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
      <i class="bi bi-newspaper"></i> Berita & Pengumuman
    </a>

    <div class="sidebar-section">Layanan</div>
    <a href="{{ route('admin.kegiatan.index') }}"
       class="nav-link {{ request()->routeIs('admin.kegiatan.*') ? 'active' : '' }}">
      <i class="bi bi-calendar-event"></i> Peminjaman Tempat
    </a>
    <a href="{{ route('admin.letter.index') }}"
       class="nav-link {{ request()->routeIs('admin.letter.*') ? 'active' : '' }}">
      <i class="bi bi-file-earmark-text"></i> Format Surat
    </a>

    <div class="sidebar-section">Hubungi Kami</div>
    <a href="{{ route('admin.media.index') }}"
       class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
      <i class="bi bi-handshake"></i> Media Partner
    </a>

    <div class="sidebar-section">Sistem</div>
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
      <i class="bi bi-person-gear"></i> Manajemen User
    </a>
    <a href="{{ route('admin.setting.index') }}"
       class="nav-link {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}">
      <i class="bi bi-gear"></i> Pengaturan
    </a>

    <div class="sidebar-section">Lainnya</div>
    <a href="{{ route('dashboard') }}" class="nav-link">
      <i class="bi bi-journal-text"></i> Modul LPJ &amp; Proposal
    </a>
    <a href="{{ route('home') }}" class="nav-link" target="_blank">
      <i class="bi bi-eye"></i> Lihat Website
    </a>
    <a href="{{ route('admin.logout') }}" class="nav-link text-danger"
       onclick="return confirm('Yakin ingin logout?')">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>
</div>

{{-- ════════════════════════════════════════════
     TOPBAR
════════════════════════════════════════════ --}}
<div class="topbar">
  <div class="topbar-left">
    {{-- Hamburger — hanya muncul di layar kecil --}}
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu">
      <i class="bi bi-list"></i>
    </button>
    <h6 class="topbar-title">@yield('title','Dashboard')</h6>
  </div>
  <div class="topbar-right">
    <div class="topbar-user">
      <span class="topbar-user-name">Halo, <strong>{{ session('admin_name') }}</strong></span>
      <span class="topbar-badge">{{ ucfirst(str_replace('_',' ', session('admin_role') ?? '')) }}</span>
    </div>
  </div>
</div>

{{-- ════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════ --}}
<div class="main-content">
  {{-- Flash messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="bi bi-exclamation-triangle me-2"></i>
      <strong>Mohon perbaiki kesalahan berikut:</strong>
      <ul class="mb-0 mt-1">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
  const sidebar  = document.getElementById('sidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const toggle   = document.getElementById('sidebarToggle');
  const closeBtn = document.getElementById('sidebarClose');

  function openSidebar() {
    sidebar.classList.add('sidebar-open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('sidebar-open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  toggle?.addEventListener('click', openSidebar);
  closeBtn?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);

  /* Tutup sidebar saat resize ke desktop */
  window.addEventListener('resize', function () {
    if (window.innerWidth >= 1024) closeSidebar();
  });

  /* Tutup sidebar saat klik nav-link di mobile */
  sidebar?.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function () {
      if (window.innerWidth < 1024) closeSidebar();
    });
  });
})();
</script>

@yield('scripts')
</body>
</html>