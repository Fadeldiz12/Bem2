@extends('layouts.public')

@section('title', 'SIGMA BEM - Sistem Manajemen RAB')

@section('content')
<style>
/* ===== BASE STYLES ===== */
:root { --purple-deep:#4a1e8a; --purple:#6d28d9; --purple-light:#8029c6; --purple-pale:#e9d8fd; }

/* ===== COMPONENTS ===== */
.hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--purple-pale); color: var(--purple); padding: 0.5rem 1.2rem; border-radius: 50px; font-size: 0.9rem; font-weight: 600; }

.btn-cta { background: var(--purple); color: #fff; padding: 0.6rem 2rem; border-radius: 12px; font-weight: 600; border: none; text-decoration: none; display: inline-block; transition: background 0.2s; cursor: pointer; }
.btn-cta:hover { background: var(--purple-deep); color: #fff; text-decoration: none; }

.btn-secondary { color: var(--purple); padding: 0.6rem 2rem; border-radius: 12px; font-weight: 600; border: 2px solid #e5e7eb; background: #fff; text-decoration: none; display: inline-block; transition: background 0.2s; }
.btn-secondary:hover { background: #f3f4f6; color: var(--purple); text-decoration: none; }

.section-bg-light { background: var(--purple-pale); border-radius: 40px; padding: 3rem 2rem; }
.section-heading { position: relative; font-weight: 700; color: var(--purple-deep); padding-bottom: 1.5rem; }
.section-heading::after { content: ""; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 60px; height: 4px; background: var(--purple); border-radius: 2px; }

.problem-card { border: 1px solid #e5e7eb; border-radius: 24px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); transition: all 0.3s; }
.problem-card:hover { box-shadow: 0 20px 30px rgba(74,30,138,.1); }

.problem-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; overflow: hidden; }
.problem-icon img { width: 100%; height: 100%; object-fit: cover; }

.icon-purple { background: #ede9fe; }
.icon-yellow { background: #fef3c7; }
.icon-red { background: #fee2e2; }

.alert-solution { background: #f3f0f9; border: 1px solid var(--purple-pale); border-radius: 12px; padding: 1rem 1.5rem; text-align: center; }

.kementerian-section { background: var(--purple-deep); border-radius: 32px; overflow: hidden; display: grid; grid-template-columns: 1fr 1fr; box-shadow: 0 20px 50px rgba(74,30,138,.2); }
.kementerian-text { padding: 3rem 2rem; display: flex; flex-direction: column; justify-content: center; }
.kementerian-image { position: relative; min-height: 300px; background: linear-gradient(135deg, #6d28d9, #8029c6); }
.kementerian-image img { width: 100%; height: 100%; object-fit: cover; }
.kementerian-logo { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 20; width: 150px; filter: drop-shadow(0 10px 25px rgba(0,0,0,.3)); }

.badge-about { display: inline-flex; align-items: center; gap: 0.75rem; background: rgba(255,255,255,.15); color: #fff; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 1.5rem; }

.advantage-card { background: #fff; border-radius: 32px; padding: 2rem; text-align: center; box-shadow: 0 8px 30px rgba(0,0,0,.04); border: 1px solid #f3f4f6; transition: all 0.3s; }
.advantage-card:hover { transform: translateY(-4px); box-shadow: 0 20px 50px rgba(0,0,0,.08); }

.advantage-icon { width: 56px; height: 56px; background: var(--purple-pale); color: var(--purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
.advantage-icon svg { width: 24px; height: 24px; }

.step-circle { width: 60px; height: 60px; background: #fff; border: 3px solid var(--purple-deep); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.5rem; font-weight: 700; color: var(--purple-deep); box-shadow: 0 4px 12px rgba(74,30,138,.08); }
.step-item { text-align: center; }
.step-item h4 { color: var(--purple-deep); }
.step-line { display: none; }

.cta-gradient { background: linear-gradient(135deg, var(--purple-deep), var(--purple-light)); border-radius: 32px; padding: 3rem 2rem; text-align: center; color: #fff; }
.cta-gradient h2 { font-size: 2rem; font-weight: 700; margin-bottom: 1rem; }
.cta-gradient p { color: #d8c4e8; margin-bottom: 2rem; }
.cta-gradient .btn-cta { background: #fff; color: var(--purple-deep); }
.cta-gradient .btn-cta:hover { background: #f3f4f6; }

/* ===== DESKTOP (768px+) ===== */
@media (min-width: 768px) {
  .step-line { display: block; position: absolute; top: 30px; left: 12%; right: 12%; border-top: 2px dashed var(--purple-pale); z-index: 0; }
  .kementerian-section { grid-template-columns: 3fr 2fr; }
}

/* ===== TABLET (768px - 1024px) ===== */
@media (max-width: 1024px) {
  .kementerian-section { grid-template-columns: 1fr; }
  .advantage-card { padding: 1.5rem; }
  .cta-gradient h2 { font-size: 1.75rem; }
}

/* ===== MOBILE (480px - 767px) ===== */
@media (max-width: 767px) {
  h1.display-3 { font-size: 2rem !important; }
  h2.display-5 { font-size: 1.5rem !important; }
  h2.section-heading { font-size: 1.5rem; }
  p.fs-5 { font-size: 0.95rem !important; }
  
  .py-5 { padding-top: 2rem !important; padding-bottom: 2rem !important; }
  .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
  
  .btn-cta, .btn-secondary { 
    padding: 0.5rem 1.2rem;
    font-size: 0.9rem;
    width: 100%;
    min-height: 44px;
  }
  
  .problem-card { padding: 1.5rem; }
  .problem-icon { width: 40px; height: 40px; }
  
  .section-bg-light { padding: 1.5rem; }
  .kementerian-section { grid-template-columns: 1fr; }
  .kementerian-text { padding: 1.5rem; }
  .kementerian-image { min-height: 200px; }
  .kementerian-logo { width: 100px; }
  
  .advantage-card { padding: 1.2rem; }
  .advantage-icon { width: 48px; height: 48px; }
  
  .step-circle { width: 50px; height: 50px; font-size: 1.2rem; }
  
  .cta-gradient { padding: 1.5rem; }
  .cta-gradient h2 { font-size: 1.3rem; }
  .cta-gradient .btn-cta { width: 100%; }
}

/* ===== SMALL MOBILE (< 480px) ===== */
@media (max-width: 479px) {
  h1.display-3 { font-size: 1.5rem !important; }
  h2.display-5 { font-size: 1.2rem !important; }
  
  .btn-cta, .btn-secondary { font-size: 0.85rem; }
  .problem-card { padding: 1.2rem; }
  .problem-icon { width: 36px; height: 36px; }
  .step-circle { width: 45px; height: 45px; }
}
</style>

<!-- Hero Section -->
<section class="py-5 px-4" style="background: #f9fafb;">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6 col-12">
        <span class="hero-badge">#Solusi Cepat RAB Himpunan</span>
        <h1 class="display-3 fw-bold text-dark mt-3 mb-4">SIGMA BEM</h1>
        <p class="text-muted fs-5 lh-lg mb-4">
          Solusi Digital Penyusunan RAB Organisasi Mahasiswa. SIGMA BEM merupakan program kerja digital yang dilandasi dari kebutuhan kegiatan mahasiswa dan memberikan kemudahan untuk pengurus...
        </p>
        <div class="d-grid gap-2 d-md-flex gap-md-3">
          <a href="{{ route('dashboard') }}" class="btn-cta">Ayo Buat RAB Sekarang →</a>
          <a href="#tentang" class="btn-secondary">Pelajari lebih lanjut</a>
        </div>
      </div>
      <div class="col-lg-6 col-12">
        <div class="bg-white p-3 rounded-4 shadow-lg">
          <img src="{{ asset('img/mockup.png') }}" alt="Dashboard Preview" class="img-fluid rounded-3">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tentang Platform Section -->
<section id="tentang" class="py-5 px-4">
  <div class="container">
    <div class="section-bg-light">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6 col-12">
          <img src="{{ asset('img/Illustration.png') }}" alt="Preview SIGMA BEM" class="img-fluid rounded-3 shadow-lg" style="border: 6px solid #fff;">
        </div>
        <div class="col-lg-6 col-12">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div style="width: 8px; height: 2px; background: var(--purple-deep);"></div>
            <span style="color: var(--purple-deep); font-weight: 700; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase;">Tentang Platform</span>
          </div>
          <h2 class="display-5 fw-bold mb-4" style="color: var(--purple-deep);">Apa itu SIGMA BEM?</h2>
          <p class="text-muted lh-lg mb-3 fs-5">
            SIGMA BEM adalah platform digital yang dirancang khusus untuk membantu bendahara HMPS dan UKM dalam menyusun Rencana Anggaran Biaya (RAB) secara lebih <span style="font-weight: 700; color: var(--purple);">mudah, cepat, dan sistematis.</span>
          </p>
          <p class="text-muted lh-lg fs-5">
            Melalui website ini, proses penyusunan anggaran diharapkan tidak lagi dilakukan secara manual, sehingga dapat meminimalkan kesalahan perhitungan, meningkatkan efisiensi kerja, dan mempermudah pengarsipan data keuangan organisasi.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Problem Section -->
<section class="py-5 px-4">
  <div class="container">
    <h2 class="text-center fw-bold mb-5">Berangkat dari Permasalahan Nyata</h2>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="problem-card">
          <div class="problem-icon icon-purple">
            <img src="{{ asset('img/bg_shadow.png') }}" alt="">
          </div>
          <h3 class="fw-bold mb-2">Penyusunan Masih Manual</h3>
          <p class="text-muted small">Penyusunan anggaran masih diproses secara manual, sehingga memakan waktu dan rentan salah.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="problem-card">
          <div class="problem-icon icon-yellow">
            <img src="{{ asset('img/warning.png') }}" alt="">
          </div>
          <h3 class="fw-bold mb-2">Rumit Keahlian/Hitung</h3>
          <p class="text-muted small">Proses hitung yang manual menyulitkan pengurus saat penyusunan anggaran kegiatan.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="problem-card">
          <div class="problem-icon icon-red">
            <img src="{{ asset('img/bg_shadow2.png') }}" alt="">
          </div>
          <h3 class="fw-bold mb-2">Pengarsipan Kurang Efisien</h3>
          <p class="text-muted small">Dokumen sering tercecer dan hilang, menyulitkan proses pertanggungjawaban.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Alert Solusi -->
<section class="px-4 mb-5">
  <div class="container">
    <div class="alert-solution">
      <span>💡</span> Solusi: Melalui SIGMA BEM, penyusunan RAB menjadi praktis, terstruktur, dan <span class="fw-bold">terdigitalisasi</span>.
    </div>
  </div>
</section>

<!-- Kementerian Card Section -->
<section class="px-4 mb-5">
  <div class="container">
    <div class="kementerian-section">
      <div class="kementerian-text">
        <div class="badge-about">Tentang Inisiator</div>
        <h2 class="display-5 fw-bold text-white mb-3 lh-base">Kementerian Keuangan dan<br>Kewirausahaan</h2>
        <p class="text-white-50 lh-lg mb-3">
          Kementerian Keuangan dan Kewirausahaan merupakan pilar strategis BEM Politeknik Negeri Medan yang berperan dalam mengelola keuangan organisasi secara <span class="fw-bold text-white">transparan, tertata, dan terpercaya</span>, sekaligus mendorong tumbuhnya semangat kewirausahaan.
        </p>
        <p class="text-white-50 lh-lg">
          Melalui pengelolaan anggaran yang sistematis, optimalisasi pendanaan, serta pengembangan inovasi berbasis ide dan karya, kementerian ini hadir untuk menjaga stabilitas organisasi sekaligus menciptakan ruang eksplorasi yang produktif bagi mahasiswa serta Kabinet Karsa Abhinaya.
        </p>
      </div>
      <div class="kementerian-image">
        <img src="{{ asset('img/Image.png') }}" alt="Gedung Polmed">
        <img src="{{ asset('img/logokabinet.png') }}" alt="Logo Kementerian" class="kementerian-logo">
      </div>
    </div>
  </div>
</section>

<!-- Keunggulan Section -->
<section class="py-5 px-4">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-primary fw-bold small" style="letter-spacing: 0.15em; text-transform: uppercase;">Keunggulan SIGMA BEM</p>
      <h2 class="section-heading">Mengelola Anggaran Jadi Lebih Mudah</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="advantage-card">
          <div class="advantage-icon">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
            </svg>
          </div>
          <h4 class="fw-bold mb-2" style="color: var(--purple-deep);">Praktis & Efisien</h4>
          <p class="text-muted small px-2">Penyusunan RAB lebih cepat dan mudah dalam satu platform digital.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="advantage-card">
          <div class="advantage-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <h4 class="fw-bold mb-2" style="color: var(--purple-deep);">Minim Kesalahan</h4>
          <p class="text-muted small px-2">Sistem hitung otomatis mengurangi risiko kesalahan perhitungan manual.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="advantage-card">
          <div class="advantage-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <h4 class="fw-bold mb-2" style="color: var(--purple-deep);">Dokumentasi Terstruktur</h4>
          <p class="text-muted small px-2">Arsip RAB tersimpan rapi dan mudah diakses kapan saja.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="advantage-card">
          <div class="advantage-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <h4 class="fw-bold mb-2" style="color: var(--purple-deep);">Kolaborasi Mudah</h4>
          <p class="text-muted small px-2">Menjadi solusi digital terpusat bagi bendahara HMPS dan UKM.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Alur Penggunaan Section -->
<section class="py-5 px-4">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-primary fw-bold small" style="letter-spacing: 0.15em; text-transform: uppercase;">Alur Penggunaan</p>
      <h2 style="color: var(--purple-deep); font-size: 2rem; font-weight: 700;">4 Langkah Mudah</h2>
    </div>
    <div style="position: relative;">
      <div class="step-line"></div>
      <div class="row g-4" style="position: relative; z-index: 10;">
        <div class="col-md-6 col-lg-3">
          <div class="step-item">
            <div class="step-circle">1</div>
            <h4 class="fw-bold small mb-2">Registrasi / Login</h4>
            <p class="text-muted" style="font-size: 0.85rem;">Daftarkan akun organisasi atau masuk jika sudah punya.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-item">
            <div class="step-circle">2</div>
            <h4 class="fw-bold small mb-2">Buat RAB Baru</h4>
            <p class="text-muted" style="font-size: 0.85rem;">Isi detail kegiatan dan rincian item anggaran kebutuhan.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-item">
            <div class="step-circle">3</div>
            <h4 class="fw-bold small mb-2">Sistem Hitung</h4>
            <p class="text-muted" style="font-size: 0.85rem;">Sistem otomatis menghitung subtotal dan grand total.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-item">
            <div class="step-circle">4</div>
            <h4 class="fw-bold small mb-2">Export Dokumen</h4>
            <p class="text-muted" style="font-size: 0.85rem;">Unduh hasil RAB dalam format PDF, Excel, atau Word.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Final -->
<section class="py-5 px-4">
  <div class="container">
    <div class="cta-gradient">
      <h2>Siap Menyusun RAB dengan Lebih Mudah?</h2>
      <p>Mulai atur RAB organisasi kamu dengan lebih rapi, terstruktur, dan efisien bersama SIGMA BEM.</p>
      <a href="{{ route('dashboard') }}" class="btn-cta">Buka Dashboard</a>
    </div>
  </div>
</section>
@endsection