@extends('layouts.public')
@section('title','Profil BEM Polmed')
@section('content')

{{-- HERO — full viewport, grid bg, blur gradients --}}
<section class="profil-hero">
  <div class="grid-bg"></div>
  <div class="blob blob-tl"></div>
  <div class="blob blob-br"></div>

  <div class="container h-100 d-flex flex-column align-items-center justify-content-center text-center position-relative">
    @if($year?->logo_path)
      <img src="{{ asset('storage/'.$year->logo_path) }}"
           class="profil-hero-logo"
           loading="eager" alt="{{ $year->cabinet_name }}">
    @endif
    <h2 class="profil-hero-title">{{ strtoupper($year?->cabinet_name ?? '') }}</h2>
    <p class="profil-hero-tagline">{{ $year?->tagline }}</p>
  </div>

  <div class="scroll-down-hint">
    <i class="bi bi-chevron-double-down"></i>
  </div>
</section>

{{-- VISI --}}
<section class="container py-5">
  <div class="row justify-content-end">
    <div class="col-md-9">
      <div class="text-end mb-2 d-flex align-items-center justify-content-end gap-2">
        <span class="fw-bold" style="color:var(--purple-deep);letter-spacing:1px">VISI</span>
        <span class="rounded-circle d-inline-block" style="width:10px;height:10px;background:var(--purple)"></span>
      </div>
      <p class="text-end" style="color:#333;line-height:1.8">{{ $year?->visi }}</p>
    </div>
  </div>
</section>

{{-- MISI --}}
<section class="container pb-5">
  <div class="d-flex align-items-center gap-2 mb-4">
    <span class="rounded-circle d-inline-block" style="width:10px;height:10px;background:var(--purple)"></span>
    <span class="fw-bold" style="color:var(--purple-deep);letter-spacing:1px">MISI</span>
  </div>
  <div class="d-flex flex-column gap-3">
    @foreach($misi_list as $i => $m)
    <div class="misi-card">
      <span class="misi-number">{{ $i + 1 }}</span>
      <p class="mb-0">{{ $m }}</p>
    </div>
    @endforeach
  </div>
</section>

{{-- FILOSOFI LOGO --}}
@if(count($filosofi_list))
<section class="container pb-5">
  <h2 class="font-serif text-center mb-5" style="color:var(--purple-deep);font-size:clamp(1.8rem,4vw,2.6rem)">
    Filosofi Logo
  </h2>
  <div class="row g-0 align-items-start">

    {{-- Kolom logo — sticky di desktop, static di mobile --}}
    <div class="col-md-5 text-center mb-4 mb-md-0 pe-md-4 logo-sticky">
      @if($year?->logo_path)
        <img src="{{ asset('storage/'.$year->logo_path) }}"
             style="max-width:280px;width:100%;filter:drop-shadow(0 8px 24px rgba(74,30,138,.2))"
             loading="lazy" alt="">
      @endif
      <p class="small fw-bold mt-3 mb-0" style="color:var(--purple);letter-spacing:1px">BEM POLITEKNIK NEGERI MEDAN</p>
      <p class="font-serif fw-bold" style="color:var(--purple-deep)">{{ strtoupper($year?->cabinet_name ?? '') }}</p>
    </div>

    {{-- Kolom daftar filosofi --}}
    <div class="col-md-7">
      @foreach($filosofi_list as $f)
      <div class="d-flex gap-3 align-items-start mb-4">
        @if(!empty($f['gambar']))
          <img src="{{ asset('storage/'.$f['gambar']) }}" style="width:56px;height:56px;object-fit:contain;flex-shrink:0" loading="lazy" alt="">
        @else
          <div style="width:56px;height:56px;flex-shrink:0"></div>
        @endif
        <div style="border-left:3px solid var(--purple);padding-left:1rem">
          <h6 class="fw-bold text-uppercase mb-1" style="color:var(--purple-deep);font-size:.82rem;letter-spacing:.5px">{{ $f['nama'] }}</h6>
          <p class="small mb-0" style="color:#444;line-height:1.7">{{ $f['penjelasan'] }}</p>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>
@endif

{{-- MAKNA WARNA --}}
@if(count($warna_list))
<section class="container pb-5">
  <h2 class="font-serif text-center mb-5" style="color:var(--purple-deep);font-size:clamp(1.8rem,4vw,2.6rem)">
    Makna Warna
  </h2>
  <div class="row g-3">
    @foreach($warna_list as $w)
    <div class="col-md-6">
      <div class="warna-card">
        <div class="warna-swatch" style="background:{{ $w['hex'] ?? '#ccc' }}"></div>
        <div>
          <div class="warna-nama">{{ $w['nama'] }} <span class="warna-hex">{{ $w['hex'] }}</span></div>
          <p class="warna-makna">{{ $w['makna'] }}</p>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</section>
@endif

{{-- STRUKTUR KEPENGURUSAN --}}
<section class="struct-section">
  <div class="blob blob-tl" style="opacity:.07"></div>
  <div class="blob blob-br" style="opacity:.07"></div>

  <div class="text-center py-5">
    <h3 class="font-serif fw-bold text-uppercase" style="color:var(--purple-deep);letter-spacing:.5px">
      Struktur Kepengurusan BEM Polmed {{ $year?->start_date?->format('Y') }}
    </h3>
    <p class="fw-semibold mb-0" style="color:var(--purple)">{{ strtoupper($year?->cabinet_name ?? '') }}</p>
  </div>

  <div class="container">
    <div class="struct-card-wrap">

      {{-- ── Presma & Wapresma
           Hanya 2 orang, tetap flex-wrap center di semua breakpoint.
           Di mobile diberi carousel juga untuk konsistensi jika layar sangat kecil.
      ──────────────────────────────────────── --}}
      <div class="struct-label mb-3">Pimpinan</div>
      <div class="struct-scroll-wrap mb-5">
        <div class="struct-scroll-inner justify-content-center">
          @foreach([$presma, $wapresma] as $p)
            @if($p)
            <div class="struct-item">
              <div class="person-col">
                <div class="person-photo-wrap">
                  @if($p->photo_path)
                    <img src="{{ asset('storage/'.$p->photo_path) }}" class="person-photo" loading="lazy" alt="{{ $p->full_name }}">
                  @else
                    <div class="person-no-photo"><i class="bi bi-person-fill"></i></div>
                  @endif
                </div>
                <div class="person-label">
                  <span class="person-name">{{ $p->full_name }}</span>
                  <span class="person-pos">{{ $p->position }}</span>
                </div>
              </div>
            </div>
            @endif
          @endforeach
        </div>
      </div>

      {{-- ── Menteri + Badge (digabung satu kolom per menteri)
           Desktop & Mobile : horizontal scroll carousel, foto + badge per kolom sinkron
      ──────────────────────────────────────── --}}
      <div class="struct-label mb-3">Menteri & Kementerian</div>

      {{-- Wrapper untuk fade hint kanan — kini scroll horizontal di desktop & mobile --}}
      <div class="struct-carousel-outer struct-carousel-outer--menteri">

          <!-- Tombol Panah -->
          <button class="carousel-arrow arrow-left hidden" id="menteriPrev">
              <i class="bi bi-chevron-left"></i>
          </button>

          <button class="carousel-arrow arrow-right hidden" id="menteriNext">
              <i class="bi bi-chevron-right"></i>
          </button>

          <div class="struct-scroll-wrap struct-scroll-wrap--menteri" id="menteriWrap">
              <div class="struct-scroll-inner struct-scroll-inner--menteri">

                  @foreach($menteri_list as $idx => $menteri)

                  <div class="struct-item"
                      id="structItem{{ $idx }}"
                      onmouseenter="syncHover({{ $idx }}, true)"
                      onmouseleave="syncHover({{ $idx }}, false)">

                      <div class="person-col" id="menteriCard{{ $idx }}">
                          <div class="person-photo-wrap">

                              @if($menteri->ministry_logo)
                                  <img src="{{ asset('storage/'.$menteri->ministry_logo) }}"
                                      class="person-ministry-bg"
                                      loading="lazy">
                              @endif

                              @if($menteri->photo_path)
                                  <img src="{{ asset('storage/'.$menteri->photo_path) }}"
                                      class="person-photo"
                                      loading="lazy"
                                      alt="{{ $menteri->full_name }}">
                              @else
                                  <div class="person-no-photo">
                                      <i class="bi bi-person-fill"></i>
                                  </div>
                              @endif

                          </div>

                          <div class="person-label">
                              <span class="person-name">{{ $menteri->full_name }}</span>
                              <span class="person-pos">{{ $menteri->position }}</span>
                          </div>
                      </div>

                      <div class="dept-badge mt-2"
                          id="deptBadge{{ $idx }}"
                          onclick="window.location='{{ route('kementerian.detail',$menteri->ministry_id) }}'">

                          @if($menteri->ministry_logo)
                              <img src="{{ asset('storage/'.$menteri->ministry_logo) }}"
                                  class="dept-badge-logo"
                                  loading="lazy">
                          @endif

                          <span class="dept-badge-name">
                              {{ $menteri->ministry_alias }}
                          </span>

                      </div>

                  </div>

                  @endforeach

              </div>
          </div>

          <div class="struct-fade-right"></div>

      </div>

    </div>
  </div>

  <div style="height:3rem"></div>
</section>

@endsection
@section('scripts')
<style>
/* ====== PROFIL HERO ====== */
.profil-hero {
  position: relative;
  min-height: calc(100vh - 70px);
  display: flex;
  flex-direction: column;
  justify-content: center;
  overflow: hidden;
  background: #f7f4ff;
}
.grid-bg {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(74,30,138,.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(74,30,138,.06) 1px, transparent 1px);
  background-size: 48px 48px;
  pointer-events: none;
}
.blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  pointer-events: none;
}
.blob-tl { width:420px;height:420px;background:rgba(180,100,230,.28);top:-100px;left:-100px; }
.blob-br  { width:380px;height:380px;background:rgba(100,80,220,.22);bottom:-80px;right:-80px; }

.profil-hero-logo {
  height: clamp(200px, 30vh, 320px);
  object-fit: contain;
  filter: drop-shadow(0 20px 48px rgba(74,30,138,.25));
  margin-bottom: 1.5rem;
}
.profil-hero-title {
  font-family: 'Playfair Display', serif;
  font-weight: 700;
  font-size: clamp(1.8rem, 4vw, 3rem);
  color: var(--purple-deep);
  letter-spacing: 1px;
  margin-bottom: .3rem;
}
.profil-hero-tagline {
  font-weight: 600;
  color: var(--purple);
  font-size: 1.05rem;
  margin: 0;
}
.scroll-down-hint {
  position: absolute;
  bottom: 1.5rem;
  left: 50%;
  transform: translateX(-50%);
  color: var(--purple);
  font-size: 1.4rem;
  animation: bounceY 1.8s infinite;
  opacity: .6;
}
@keyframes bounceY {
  0%,100% { transform: translateX(-50%) translateY(0); }
  50%      { transform: translateX(-50%) translateY(8px); }
}

/* ====== MISI ====== */
.misi-card {
  background: #fff;
  border: 2px solid var(--purple-pale);
  border-radius: 14px;
  padding: 1rem 1.5rem;
  display: flex; align-items: flex-start; gap: 1.2rem;
  transition: background .25s, border-color .25s, transform .2s, color .25s;
}
.misi-card:hover {
  background: var(--purple-deep);
  border-color: var(--purple-deep);
  color: #fff;
  transform: translateX(8px);
}
.misi-number {
  min-width:40px;height:40px;border-radius:10px;
  background:var(--purple);color:#fff;
  font-weight:800;font-size:1.1rem;
  display:flex;align-items:center;justify-content:center;flex-shrink:0;
  transition: background .25s;
}
.misi-card:hover .misi-number { background: rgba(255,255,255,.22); }

/* ====== FILOSOFI LOGO ====== */
.logo-sticky { position: sticky; top: 90px; }

@media (max-width: 767px) {
  .logo-sticky {
    position: static;
    top: auto;
    padding-bottom: 1.5rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid rgba(74,30,138,.12);
  }
  .logo-sticky img { max-width: 180px !important; }
}

/* ====== MAKNA WARNA ====== */
.warna-card { display:flex;align-items:center;gap:1rem;background:#fff;border-radius:16px;padding:.9rem 1.2rem;box-shadow:0 2px 12px rgba(74,30,138,.07); }
.warna-swatch { width:48px;height:48px;border-radius:10px;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,.14); }
.warna-nama { font-weight:700;font-size:.88rem;color:var(--purple-deep); }
.warna-hex { font-size:.72rem;color:#999;font-weight:400; }
.warna-makna { font-size:.8rem;color:#555;margin:0; }

/* ====== STRUCT SECTION ====== */
.struct-section {
  position: relative;
  background: linear-gradient(160deg, #f0eaff 0%, #e8e0ff 50%, #f5f0ff 100%);
  overflow: hidden;
}
.struct-card-wrap {
  background: rgba(255,255,255,.55);
  backdrop-filter: blur(6px);
  border-radius: 32px;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 48px rgba(74,30,138,.1);
  border: 1px solid rgba(255,255,255,.8);
}

/* ── Label section dalam struct ── */
.struct-label {
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--purple);
  display: flex;
  align-items: center;
  gap: 1rem;
}
.struct-label::before,
.struct-label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, transparent, rgba(74,30,138,.2), transparent);
}

/* ══════════════════════════════════════════════
   STRUCT SCROLL — Desktop: flex wrap center
                   Mobile : horizontal carousel
══════════════════════════════════════════════ */
.struct-carousel-outer {
  position: relative; /* untuk fade kanan */
}

.struct-scroll-wrap {
  overflow: visible; /* desktop: tidak perlu scroll */
}

.struct-scroll-inner {
  display: flex;
  flex-wrap: wrap;         /* desktop: wrap ke baris baru */
  justify-content: center;
  gap: 1.25rem;
  align-items: flex-start;
}

/* Tiap kolom (foto + badge) */
.struct-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;          /* penting untuk carousel mobile */
}

/* Fade hint kanan — default disembunyikan, di-override untuk menteri di bawah */
.struct-fade-right { display: none; }

/* ══════════════════════════════════════════════
   MENTERI — horizontal scroll di SEMUA layar
   (beda dari Pimpinan yang tetap wrap & center)
══════════════════════════════════════════════ */
.struct-scroll-wrap--menteri {
  overflow-x: auto;
  overflow-y: visible;
  scrollbar-width: none;
}
.struct-scroll-wrap--menteri::-webkit-scrollbar { display: none; }

.struct-scroll-inner--menteri {
  flex-wrap: nowrap;
  justify-content: flex-start;
  padding: .5rem .25rem 1.25rem .25rem;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
}
.struct-scroll-inner--menteri .struct-item {
  scroll-snap-align: start;
}

/* Tampilkan fade hint kanan untuk menteri di semua ukuran layar */
.struct-carousel-outer--menteri .struct-fade-right {
  display: block;
}

/* ===============================
   Arrow Carousel Menteri
================================ */

.carousel-arrow{
    position:absolute;
    top:45%;
    transform:translateY(-50%);
    width:42px;
    height:42px;
    border:none;
    border-radius:50%;
    background:#fff;
    color:var(--purple-deep);
    box-shadow:0 6px 18px rgba(0,0,0,.15);
    z-index:100;
    cursor:pointer;
    transition:.25s;
}

.carousel-arrow:hover{
    transform:translateY(-50%) scale(1.08);
}

.arrow-left{
    left:8px;
}

.arrow-right{
    right:8px;
}

.carousel-arrow.hidden{
    opacity:0;
    pointer-events:none;
}

@media(max-width:767px){

    .carousel-arrow{
        width:36px;
        height:36px;
        font-size:.9rem;
    }

}

/* ══════════════════════════════════════════════
   MOBILE  ≤ 767px
══════════════════════════════════════════════ */
@media (max-width: 767px) {

  /* Kurangi padding struct-card-wrap di mobile */
  .struct-card-wrap {
    padding: 1.5rem 0 1.5rem 0;
    border-radius: 20px;
  }
  .struct-label { padding: 0 1rem; }

  /* Aktifkan horizontal scroll */
  .struct-scroll-wrap {
    overflow-x: auto;
    overflow-y: visible;
    scrollbar-width: none;
  }
  .struct-scroll-wrap::-webkit-scrollbar { display: none; }

  .struct-scroll-inner {
    flex-wrap: nowrap;               /* card tidak turun ke baris baru */
    justify-content: flex-start;
    gap: 1rem;
    padding: .5rem 1rem 1.25rem 1rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }

  /* Tiap item snap ke kiri */
  .struct-item {
    scroll-snap-align: start;
  }

  /* Fade hint kanan — muncul di mobile */
  .struct-fade-right {
    display: block;
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 48px;
    background: linear-gradient(to left, rgba(240,234,255,.95), transparent);
    pointer-events: none;
    border-radius: 0 20px 20px 0;
  }

  /* Ukuran foto lebih kecil di mobile */
  .person-photo-wrap { width: 90px; height: 116px; }
  .person-photo      { width: 90px; height: 116px; }
  .person-no-photo   { width: 90px; height: 116px; font-size: 2.2rem; }
  .person-label      { width: 100px; }
  .person-col        { width: 110px; }
}

/* ====== PERSON CARD ====== */
.person-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 130px;
  position: relative;
  cursor: default;
  transition: transform .25s;
}
.person-col:hover,
.person-col.hovered { transform: translateY(-6px); }

.person-photo-wrap {
  position: relative;
  width: 110px;
  height: 140px;
  margin-bottom: 0;
}
.person-photo {
  width: 110px;
  height: 140px;
  object-fit: cover;
  object-position: top;
  border-radius: 12px;
  filter:
    drop-shadow(0 0 0px #fff)
    drop-shadow(0 0 3px rgba(74,30,138,.7))
    drop-shadow(0 0 6px rgba(74,30,138,.3));
  position: relative;
  z-index: 2;
}
.person-ministry-bg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: contain;
  opacity: 0;
  transition: opacity .3s;
  z-index: 1;
  padding: 8px;
}
.struct-item:hover .person-ministry-bg,
.person-col.hovered .person-ministry-bg { opacity: .75; }

.person-no-photo {
  width: 110px; height: 140px;
  border-radius: 12px;
  background: var(--purple-pale);
  display: flex; align-items: center; justify-content: center;
  font-size: 3rem; color: var(--purple); opacity: .5;
}
.person-label {
  position: relative;
  margin-top: -22px;
  z-index: 3;
  background: rgba(255,255,255,.92);
  backdrop-filter: blur(4px);
  border-radius: 10px;
  padding: .35rem .6rem;
  box-shadow: 0 2px 12px rgba(74,30,138,.12);
  border: 1px solid rgba(255,255,255,.9);
  text-align: center;
  width: 120px;
}
.person-name { display:block;font-size:.72rem;font-weight:700;color:var(--purple-deep);line-height:1.3; }
.person-pos  { display:block;font-size:.65rem;color:var(--purple); }

/* ====== DEPT BADGE ====== */
.dept-badge {
  background:#fff;border-radius:12px;padding:.6rem .8rem;
  box-shadow:0 2px 10px rgba(74,30,138,.08);
  display:flex;flex-direction:column;align-items:center;gap:.35rem;
  width: 130px;
  cursor:pointer;transition:box-shadow .25s,transform .25s;
}
.struct-item:hover .dept-badge,
.dept-badge.hovered {
  box-shadow:0 8px 24px rgba(74,30,138,.22);
  transform:translateY(-4px);
}
.dept-badge-logo { height:32px;width:32px;object-fit:contain; }
.dept-badge-name { font-size:.68rem;font-weight:700;color:var(--purple-deep);text-align:center;line-height:1.3; }

@media (max-width: 767px) {
  .dept-badge { width: 110px; }
}
</style>

<script>
// syncHover: hover pada struct-item sudah handled via CSS (.struct-item:hover)
// Fungsi ini tetap ada untuk kompatibilitas jika ada tempat lain yang memanggilnya
function syncHover(idx, isEnter) {
  document.getElementById('menteriCard' + idx)?.classList.toggle('hovered', isEnter);
  document.getElementById('deptBadge'   + idx)?.classList.toggle('hovered', isEnter);
}

const menteriWrap = document.getElementById("menteriWrap");
const btnPrev = document.getElementById("menteriPrev");
const btnNext = document.getElementById("menteriNext");

function updateMenteriArrow(){

    if(!menteriWrap) return;

    const maxScroll =
        menteriWrap.scrollWidth - menteriWrap.clientWidth;

    if(maxScroll <= 5){

        btnPrev.classList.add("hidden");
        btnNext.classList.add("hidden");
        return;

    }

    if(menteriWrap.scrollLeft <= 5){
        btnPrev.classList.add("hidden");
    }else{
        btnPrev.classList.remove("hidden");
    }

    if(menteriWrap.scrollLeft >= maxScroll-5){
        btnNext.classList.add("hidden");
    }else{
        btnNext.classList.remove("hidden");
    }

}

btnPrev.addEventListener("click",function(){

    menteriWrap.scrollBy({
        left:-260,
        behavior:"smooth"
    });

});

btnNext.addEventListener("click",function(){

    menteriWrap.scrollBy({
        left:260,
        behavior:"smooth"
    });

});

menteriWrap.addEventListener("scroll",updateMenteriArrow);

window.addEventListener("load",updateMenteriArrow);

window.addEventListener("resize",updateMenteriArrow);
</script>
@endsection