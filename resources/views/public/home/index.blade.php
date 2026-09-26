@extends('layouts.public')
@section('title','Beranda')
@section('meta_title','BEM Polmed | Badan Eksekutif Mahasiswa Politeknik Negeri Medan')
@section('content')

{{-- HERO SECTION — full viewport height --}}
<section class="hero-section">
  <div class="grid-bg"></div>
  <div class="blob blob-tl"></div>
  <div class="blob blob-br"></div>
  
  <div class="container h-100 position-relative">
    <div class="row align-items-center h-100">
      <div class="col-lg-7">
        <span class="hero-badge mb-4 d-inline-flex">
          <i class="bi bi-circle-fill me-2" style="font-size:.5rem"></i> Selamat Datang di Website Resmi
        </span>
        <h1 class="font-serif fw-bold mb-3" style="color:var(--purple-deep); font-size:clamp(2.2rem,5vw,3.8rem); line-height:1.1">
          <span class="d-block fs-4 fw-normal lh-base mb-1" style="color:var(--purple)">BEM POLMED {{ $year?->year_label }}</span>
          {{ strtoupper($year?->cabinet_name ?? '') }}
        </h1>
        @if($year?->start_date)
          <p class="text-muted mb-4">Periode {{ $year->start_date->format('Y') }} – {{ $year->end_date->format('Y') }}</p>
        @endif
        <a href="{{ route('profil') }}" class="btn btn-dark-purple btn-lg">Kenali Kami</a>
      </div>
      <div class="col-lg-5 text-center mt-5 mt-lg-0">
        @if($year?->logo_path)
          <img src="{{ asset('storage/'.$year->logo_path) }}"
               style="max-height:380px;max-width:100%;object-fit:contain;filter:drop-shadow(0 20px 40px rgba(74,30,138,.25))"
               loading="eager" alt="Logo {{ $year->cabinet_name }}">
        @endif
      </div>
    </div>
  </div>
  <div class="scroll-indicator">
    <i class="bi bi-chevron-double-down"></i>
  </div>
</section>

{{-- APA ITU BEM + VISI --}}
<section class="container py-5">
  <div class="section-purple">
    <div class="row g-4 align-items-center">
      <div class="col-md-7">
        <span class="badge bg-white text-success rounded-pill px-3 py-2 mb-3 d-inline-flex align-items-center gap-2">
          <i class="bi bi-circle-fill" style="font-size:.5rem"></i>Apa itu Bem Polmed
        </span>
        <p>
          <strong>Badan Eksekutif Mahasiswa (BEM)</strong> adalah lembaga eksekutif dalam struktur
          pemerintahan mahasiswa di tingkat Perguruan Tinggi yang bertugas menjalankan program kerja,
          menyalurkan aspirasi mahasiswa, serta berperan sebagai mitra dialog dengan pihak kampus.
          Sebagai representasi mahasiswa, BEM berfungsi mengkoordinasikan kegiatan kemahasiswaan,
          mengadvokasi kepentingan mahasiswa, dan mengembangkan potensi mahasiswa melalui berbagai program.
        </p>
      </div>
      <div class="col-md-5">
        <div class="placeholder-img-box">
        <img src="{{ asset('images/logo_bem_polmed.webp') }}"
            class="img-fluid rounded-3"
            style="max-height:260px;object-fit:contain;filter:drop-shadow(0 8px 24px rgba(74,30,138,.15))"
            alt="Logo BEM Polmed">
        </div>
      </div>
    </div>

    <div class="row g-4 align-items-center mt-2">
      <div class="col-md-5 order-md-1">
        <div class="placeholder-img-box">
        <img src="{{ asset('images/logo-polmed-png.webp') }}"
            class="img-fluid rounded-3"
            style="max-height:260px;object-fit:contain;filter:drop-shadow(0 8px 24px rgba(74,30,138,.15))"
            alt="Logo BEM Polmed">
        </div>
      </div>
      <div class="col-md-7 order-md-2">
        <span class="badge bg-white text-success rounded-pill px-3 py-2 mb-3 d-inline-flex align-items-center gap-2">
          <i class="bi bi-circle-fill" style="font-size:.5rem"></i>Visi
        </span>
        <p class="mb-0">{{ $year?->visi }}</p>
      </div>
    </div>
  </div>
</section>

{{-- MISI --}}
<section class="container pb-5">
  <span class="badge px-3 py-2 mb-3 d-inline-flex align-items-center gap-2" style="background:var(--purple-pale);color:var(--purple)">
    <i class="bi bi-circle-fill" style="font-size:.5rem"></i>Misi
  </span>
  <ol class="mt-2">
    @foreach($misi_list as $m)
      <li class="mb-2" style="color:var(--purple-deep)">{{ $m }}</li>
    @endforeach
  </ol>
</section>

{{-- KEMENTERIAN CAROUSEL --}}
<section class="container pb-5 text-center">
  <h6 class="text-uppercase fw-semibold mb-1" style="color:var(--purple)">Kementerian</h6>
  <h4 class="font-serif fw-bold mb-4" style="color:var(--purple-deep)">BEM Polmed {{ $year?->year_label }}</h4>

  <div class="ministry-carousel-root" id="ministryCarousel">

    {{-- ── Prev / Next buttons ── --}}
    <button class="mc-btn mc-btn-prev" id="mcPrev" aria-label="Sebelumnya">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="mc-btn mc-btn-next" id="mcNext" aria-label="Berikutnya">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>

    {{-- ── Track ── --}}
    <div class="mc-viewport" id="mcViewport">
      <div class="mc-track" id="mcTrack">
        @foreach($ministries as $m)
        <div class="mc-item">
          <a href="{{ route('kementerian.detail', $m->id) }}" class="text-decoration-none">
            <div class="ministry-card">
              @if($m->logo_path)
                <img src="{{ asset('storage/'.$m->logo_path) }}"
                     loading="lazy" alt="{{ $m->name }}"
                     class="ministry-logo">
              @else
                <div class="ministry-logo-placeholder">
                  <i class="bi bi-building"></i>
                </div>
              @endif
              <span class="ministry-name">{{ $m->alias ?: $m->name }}</span>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>

    {{-- ── Auto-play progress bar ── --}}
    <div class="mc-progress-wrap">
      <div class="mc-progress-bar" id="mcProgress"></div>
    </div>

    {{-- ── Dots ── --}}
    <div class="mc-dots" id="mcDots"></div>

  </div>
</section>

{{-- BERITA TERBARU --}}
@if($recent_posts->count())
<section class="container pb-5">
  <h4 class="font-serif fw-bold mb-4 text-center" style="color:var(--purple-deep)">
    Berita & Pengumuman Terbaru
  </h4>
  <div class="row g-4">
    @foreach($recent_posts as $p)
    <div class="col-md-4">
      <div class="card-soft h-100 overflow-hidden">
        @if($p->featured_image)
          <img src="{{ asset('storage/'.$p->featured_image) }}"
               class="w-100" style="height:160px;object-fit:cover"
               loading="lazy" alt="{{ $p->title }}">
        @endif
        <div class="p-3">
          <span class="badge mb-2" style="background:var(--purple-pale);color:var(--purple-deep)">{{ $p->category }}</span>
          <h6 class="fw-bold">{{ $p->title }}</h6>
          <a href="{{ route('berita.detail', $p->slug) }}" class="small fw-semibold" style="color:var(--purple)">
            Baca selengkapnya →
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif

@endsection

@section('scripts')
<style>
/* ===== HERO ===== */
.hero-section {
  min-height: calc(100vh - 70px);
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  padding: 3rem 0 5rem;
  overflow: hidden;
  background: #f7f4ff;
}
.grid-bg {
  position:absolute;inset:0;pointer-events:none;
  background-image:
    linear-gradient(rgba(74,30,138,.06) 1px,transparent 1px),
    linear-gradient(90deg,rgba(74,30,138,.06) 1px,transparent 1px);
  background-size:48px 48px;
}
.blob { position:absolute;border-radius:50%;filter:blur(80px);pointer-events:none; }
.blob-tl { width:420px;height:420px;background:rgba(180,100,230,.28);top:-100px;left:-100px; }
.blob-br { width:380px;height:380px;background:rgba(100,80,220,.22);bottom:-80px;right:-80px; }
.hero-section .container { position:relative;z-index:2; }
.scroll-indicator {
  position:absolute;bottom:1.5rem;left:50%;transform:translateX(-50%);
  color:var(--purple);font-size:1.4rem;animation:bounce 1.8s infinite;opacity:.6;z-index:3;
}
@keyframes bounce {
  0%,100%{transform:translateX(-50%) translateY(0);}
  50%{transform:translateX(-50%) translateY(8px);}
}

/* ===== PLACEHOLDER ===== */
.placeholder-img-box {
  background:rgba(255,255,255,.25);border-radius:16px;aspect-ratio:4/3;
  display:flex;align-items:center;justify-content:center;
  font-size:3rem;color:rgba(255,255,255,.5);
}

/* ═══════════════════════════════════════════════════
   MINISTRY CAROUSEL
═══════════════════════════════════════════════════ */
.ministry-carousel-root {
  position: relative;
  padding: 0 0 3rem; /* ruang untuk dots & progress */
}

/* ── Viewport & Track ── */
.mc-viewport {
  overflow: hidden;
  margin: 0 64px; /* ruang untuk tombol prev/next */
  border-radius: 16px;
}
.mc-track {
  display: flex;
  gap: 20px;
  transition: transform .5s cubic-bezier(.4,0,.2,1);
  will-change: transform;
}

/* ── Card Item ── */
.mc-item {
  flex: 0 0 calc(20% - 16px); /* 5 cards visible */
  min-width: 0;
}
@media (max-width: 991px) {
  .mc-item { flex: 0 0 calc(33.333% - 14px); }
  .mc-viewport { margin: 0 52px; }
}
@media (max-width: 576px) {
  .mc-item { flex: 0 0 calc(50% - 10px); }
  .mc-viewport { margin: 0 44px; }
}

/* ── Ministry Card ── */
.ministry-card {
  background: #fff;
  border-radius: 20px;
  padding: 1.8rem 1rem 1.4rem;
  box-shadow: 0 4px 20px rgba(74,30,138,.08);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: .8rem;
  transition: box-shadow .3s, transform .3s;
  height: 100%;
}
.ministry-card:hover {
  box-shadow: 0 12px 36px rgba(74,30,138,.2);
  transform: translateY(-6px);
}
.ministry-logo {
  height: 90px; width: 90px;
  object-fit: contain;
  filter: grayscale(100%);
  transition: filter .35s, transform .35s;
}
.ministry-logo-placeholder {
  height: 90px; width: 90px;
  display: flex; align-items: center; justify-content: center;
  font-size: 2.8rem; color: #ccc;
  filter: grayscale(100%);
  transition: filter .35s, transform .35s;
}
.ministry-card:hover .ministry-logo,
.ministry-card:hover .ministry-logo-placeholder {
  filter: grayscale(0%);
  transform: scale(1.15);
}
.ministry-name {
  font-size: .82rem; font-weight: 700;
  color: var(--purple-deep); text-align: center; line-height: 1.3;
}

/* ── Prev / Next Buttons (baru, lebih menarik) ── */
.mc-btn {
  position: absolute;
  top: calc(50% - 1.5rem); /* 1.5rem = setengah tinggi progress+dots */
  transform: translateY(-50%);
  width: 44px; height: 44px;
  border-radius: 50%;
  border: 2px solid rgba(74,30,138,.15);
  background: #fff;
  color: var(--purple-deep);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  z-index: 10;
  box-shadow: 0 4px 16px rgba(74,30,138,.15);
  transition: background .25s, border-color .25s, color .25s,
              box-shadow .25s, transform .25s;
}
.mc-btn:hover {
  background: var(--purple-deep);
  border-color: var(--purple-deep);
  color: #fff;
  box-shadow: 0 8px 24px rgba(74,30,138,.35);
  transform: translateY(-50%) scale(1.08);
}
.mc-btn:disabled {
  opacity: .3;
  cursor: default;
  transform: translateY(-50%) scale(1);
  box-shadow: none;
}
.mc-btn-prev { left: 0; }
.mc-btn-next { right: 0; }

/* ── Progress Bar (auto-play indicator) ── */
.mc-progress-wrap {
  height: 3px;
  background: var(--purple-pale);
  border-radius: 99px;
  margin: 1.2rem 64px 0;
  overflow: hidden;
}
.mc-progress-bar {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, var(--purple-deep), var(--purple-light));
  border-radius: 99px;
  transition: width linear; /* durasi diset dari JS */
}
@media (max-width: 991px) { .mc-progress-wrap { margin: 1.2rem 52px 0; } }
@media (max-width: 576px)  { .mc-progress-wrap { margin: 1.2rem 44px 0; } }

/* ── Dots ── */
.mc-dots {
  display: flex;
  justify-content: center;
  gap: .4rem;
  margin-top: .9rem;
}
.mc-dot {
  width: 8px; height: 8px;
  border-radius: 99px;
  background: var(--purple-pale);
  border: none; padding: 0; cursor: pointer;
  transition: background .3s, width .3s;
}
.mc-dot.active {
  background: var(--purple-deep);
  width: 22px; /* pill shape saat aktif */
}
</style>

<script>
(function () {
  const viewport  = document.getElementById('mcViewport');
  const track     = document.getElementById('mcTrack');
  const prevBtn   = document.getElementById('mcPrev');
  const nextBtn   = document.getElementById('mcNext');
  const dotsWrap  = document.getElementById('mcDots');
  const progressBar = document.getElementById('mcProgress');
  if (!track || !track.children.length) return;

  const AUTOPLAY_DELAY = 3500; // ms antar slide
  let current   = 0;
  let autoTimer = null;
  let progTimer = null;
  let isPaused  = false;

  /* ── Helpers ── */
  function visibleCount() {
    if (window.innerWidth <= 576) return 2;
    if (window.innerWidth <= 991) return 3;
    return 5;
  }
  function totalItems() { return track.querySelectorAll('.mc-item').length; }
  function maxIndex()   { return Math.max(0, totalItems() - visibleCount()); }
  function getItemWidth() {
    const item = track.querySelector('.mc-item');
    return item ? item.offsetWidth + 20 : 0; // 20 = gap
  }

  /* ── Render ── */
  function goTo(idx, restart = true) {
    current = Math.max(0, Math.min(idx, maxIndex()));
    track.style.transform = `translateX(-${current * getItemWidth()}px)`;
    prevBtn.disabled = current <= 0;
    nextBtn.disabled = current >= maxIndex();
    updateDots();
    if (restart) restartAutoplay();
  }

  /* ── Dots ── */
  function buildDots() {
    dotsWrap.innerHTML = '';
    const count = maxIndex() + 1;
    for (let i = 0; i < count; i++) {
      const d = document.createElement('button');
      d.className = 'mc-dot' + (i === 0 ? ' active' : '');
      d.setAttribute('aria-label', `Slide ${i + 1}`);
      d.addEventListener('click', () => goTo(i));
      dotsWrap.appendChild(d);
    }
  }
  function updateDots() {
    dotsWrap.querySelectorAll('.mc-dot').forEach((d, i) =>
      d.classList.toggle('active', i === current)
    );
  }

  /* ── Auto-play + progress bar ── */
  function startProgress() {
    progressBar.style.transition = 'none';
    progressBar.style.width = '0%';
    // force reflow
    void progressBar.offsetWidth;
    progressBar.style.transition = `width ${AUTOPLAY_DELAY}ms linear`;
    progressBar.style.width = '100%';
  }
  function stopProgress() {
    progressBar.style.transition = 'none';
    progressBar.style.width = '0%';
  }
  function restartAutoplay() {
    clearTimeout(autoTimer);
    clearTimeout(progTimer);
    if (isPaused) return;
    startProgress();
    autoTimer = setTimeout(() => {
      const next = current >= maxIndex() ? 0 : current + 1;
      goTo(next, false);
      restartAutoplay();
    }, AUTOPLAY_DELAY);
  }

  /* ── Pause saat hover ── */
  viewport.addEventListener('mouseenter', () => {
    isPaused = true;
    clearTimeout(autoTimer);
    stopProgress();
  });
  viewport.addEventListener('mouseleave', () => {
    isPaused = false;
    restartAutoplay();
  });

  /* ── Prev / Next ── */
  prevBtn.addEventListener('click', () => goTo(current - 1));
  nextBtn.addEventListener('click', () => goTo(current + 1));

  /* ── Swipe gesture (touch + mouse drag) ── */
  let startX = 0, isDragging = false, dragDelta = 0;

  function onDragStart(clientX) {
    startX    = clientX;
    isDragging = true;
    dragDelta  = 0;
    clearTimeout(autoTimer);
    stopProgress();
    track.style.transition = 'none';
  }
  function onDragMove(clientX) {
    if (!isDragging) return;
    dragDelta = clientX - startX;
    track.style.transform =
      `translateX(${-current * getItemWidth() + dragDelta}px)`;
  }
  function onDragEnd() {
    if (!isDragging) return;
    isDragging = false;
    track.style.transition = 'transform .5s cubic-bezier(.4,0,.2,1)';
    const threshold = getItemWidth() * 0.25; // 25% lebar card
    if (dragDelta < -threshold && current < maxIndex()) {
      goTo(current + 1);
    } else if (dragDelta > threshold && current > 0) {
      goTo(current - 1);
    } else {
      goTo(current); // kembali ke posisi semula
    }
  }

  // Touch events
  viewport.addEventListener('touchstart', e => onDragStart(e.touches[0].clientX), { passive: true });
  viewport.addEventListener('touchmove',  e => onDragMove(e.touches[0].clientX),  { passive: true });
  viewport.addEventListener('touchend',   onDragEnd);

  // Mouse drag (desktop)
  viewport.addEventListener('mousedown',  e => { onDragStart(e.clientX); e.preventDefault(); });
  window.addEventListener  ('mousemove',  e => { if (isDragging) onDragMove(e.clientX); });
  window.addEventListener  ('mouseup',    onDragEnd);

  /* ── Resize ── */
  window.addEventListener('resize', () => {
    buildDots();
    goTo(Math.min(current, maxIndex()));
  });

  /* ── Init ── */
  buildDots();
  requestAnimationFrame(() => goTo(0));
})();
</script>
@endsection