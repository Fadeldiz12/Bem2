@extends('layouts.public')
@section('title', $ministry->name)
@section('content')

{{-- HERO --}}
<section class="ministry-hero">
  <div class="grid-bg"></div>
  <div class="blob blob-tl"></div>
  <div class="blob blob-br"></div>
  <div class="container h-100 d-flex flex-column align-items-center justify-content-center text-center position-relative">
    @if($ministry->logo_path)
      <img src="{{ asset('storage/'.$ministry->logo_path) }}" class="ministry-hero-logo" loading="eager" alt="{{ $ministry->name }}">
    @endif
    <p class="small text-muted mb-1">Kementerian</p>
    <h2 class="ministry-hero-title">{{ strtoupper($ministry->name) }}</h2>
    <div class="d-flex gap-2 flex-wrap justify-content-center mt-3">
      @foreach($ministry->departments as $i => $d)
        <span class="badge rounded-pill px-3 py-2"
          style="background:{{ $i===0 ? 'var(--purple)' : 'var(--purple-pale)' }};color:{{ $i===0 ? '#fff' : 'var(--purple-deep)' }}">
          Dept. {{ $d->name }}
        </span>
      @endforeach
    </div>
  </div>
  <div class="scroll-down-hint"><i class="bi bi-chevron-double-down"></i></div>
</section>

{{-- INFO KEMENTERIAN --}}
@if($ministry->description || $ministry->tugas_pokok)
<section class="container pt-5">
  <div class="section-purple">
    @if($ministry->description)<p class="mb-3">{{ $ministry->description }}</p>@endif
    @if($ministry->tugas_pokok)
      <details>
        <summary class="btn btn-light btn-sm rounded-pill" style="color:var(--purple-deep)">Tugas Pokok dan Fungsi</summary>
        <div class="bg-white text-dark rounded-3 p-3 small mt-2" style="line-height:1.7">{{ nl2br(e($ministry->tugas_pokok)) }}</div>
      </details>
    @endif
  </div>
</section>
@endif

{{-- MENTERI --}}
@if($ministry->menteri)
<section class="container py-5 text-center">
  <p class="text-uppercase fw-bold small mb-4" style="color:var(--purple);letter-spacing:2px">Menteri</p>
  <div class="d-flex justify-content-center">
    <div class="member-card member-card--minister">
      <div class="member-card__img-wrap">

        {{-- Logo kementerian — background saat hover --}}
        @if($ministry->logo_path)
          <img src="{{ asset('storage/'.$ministry->logo_path) }}"
               class="member-card__ministry-bg" loading="lazy" alt="">
        @endif

        @if($ministry->menteri->photo_path)
          <img src="{{ asset('storage/'.$ministry->menteri->photo_path) }}" class="member-card__img" loading="lazy" alt="{{ $ministry->menteri->full_name }}">
        @else
          <div class="member-card__placeholder"><i class="bi bi-person-fill"></i></div>
        @endif
        <div class="member-card__overlay member-card__overlay--hidden">
          <span class="member-card__overlay-name">{{ $ministry->menteri->full_name }}</span>
          <span class="member-card__overlay-role">{{ $ministry->menteri->position }}</span>
        </div>
      </div>
      <div class="member-card__label member-card__label--always">
        <span class="member-card__name">{{ $ministry->menteri->full_name }}</span>
        <span class="member-card__role">{{ $ministry->menteri->position }}</span>
      </div>
    </div>
  </div>
</section>
@endif

{{-- DEPARTEMEN --}}
@php
$statusColors = ['akan_dilaksanakan'=>'#6b7280','sedang_berjalan'=>'#f59e0b','selesai'=>'#10b981'];
$statusLabels = ['akan_dilaksanakan'=>'Akan Dilaksanakan','sedang_berjalan'=>'Sedang Berjalan','selesai'=>'Selesai'];
@endphp

@foreach($ministry->departments as $d)
<section class="container pb-5">
  <h4 class="fw-bold mb-4 text-center">
    Departemen <span class="fst-italic" style="color:var(--purple)">{{ $d->name }}</span>
  </h4>

  @if($d->description || $d->tugas_pokok)
  <div class="card-soft p-3 mb-4">
    @if($d->description)<p class="small mb-2">{{ $d->description }}</p>@endif
    @if($d->tugas_pokok)<p class="small text-muted mb-0"><strong>Fungsi:</strong> {{ $d->tugas_pokok }}</p>@endif
  </div>
  @endif

  {{-- Program Kerja --}}
  @if($d->programs->count())
  <div class="mt-4 mb-4">
    <p class="text-center small fw-semibold mb-3" style="color:var(--purple)">Program Kerja</p>
    <div class="row g-2 justify-content-center">
      @foreach($d->programs as $pk)
      <div class="col-md-4">
        <div class="rounded-3 px-3 py-2 text-white small" style="background:{{ $statusColors[$pk->status] ?? '#6b7280' }}">
          <strong>{{ $pk->name }}</strong>
          <span class="d-block" style="font-size:.7rem;opacity:.9">
            {{ $statusLabels[$pk->status] ?? $pk->status }}{{ $pk->execution_date ? ' · '.$pk->execution_date->format('d M Y') : '' }}
          </span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Anggota Departemen --}}
  @php
    $members = collect();
    if($d->head){
      $members->push(['person' => $d->head, 'role' => 'Kepala Departemen']);
    }
    foreach($d->staff as $staff){
      $members->push(['person' => $staff, 'role' => 'Staff']);
    }
  @endphp

  @if($members->count())
  <div>
    <p class="text-center small fw-semibold mb-3" style="color:var(--purple)">Struktur {{ $d->name }}</p>

    <div class="member-carousel-outer">
      <div class="member-scroll-wrap">
        <div class="member-scroll-inner">
          @foreach($members as $item)
          @php $mem = $item['person']; @endphp
          <div class="member-card">
            <div class="member-card__img-wrap">

              {{-- Logo kementerian — background saat hover --}}
              @if($ministry->logo_path)
                <img src="{{ asset('storage/'.$ministry->logo_path) }}"
                     class="member-card__ministry-bg" loading="lazy" alt="">
              @endif

              @if($mem->photo_path)
                <img src="{{ asset('storage/'.$mem->photo_path) }}" class="member-card__img" loading="lazy" alt="{{ $mem->full_name }}">
              @else
                <div class="member-card__placeholder"><i class="bi bi-person-fill"></i></div>
              @endif
              <div class="member-card__overlay member-card__overlay--show">
                <span class="member-card__overlay-name">{{ $mem->full_name }}</span>
                <span class="member-card__overlay-role">{{ $item['role'] }}</span>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="member-fade-right"></div>
    </div>
  </div>
  @endif

</section>
@endforeach

@endsection

@section('scripts')
<style>
/* ====== HERO ====== */
.ministry-hero {
  position:relative;min-height:calc(100vh - 70px);
  display:flex;flex-direction:column;justify-content:center;
  overflow:hidden;background:#f7f4ff;
}
.ministry-hero-logo {
  width: 400px !important;
  height: 400px !important;
  object-fit: contain;
  filter: drop-shadow(0 16px 40px rgba(74,30,138,.2));
  margin-bottom: 1.2rem;
}
.ministry-hero-title {
  font-family:'Playfair Display',serif;font-weight:700;
  font-size:clamp(1.6rem,3.5vw,2.6rem);color:var(--purple-deep);letter-spacing:1px;
}
.grid-bg {
  position:absolute;inset:0;pointer-events:none;
  background-image:linear-gradient(rgba(74,30,138,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(74,30,138,.06) 1px,transparent 1px);
  background-size:48px 48px;
}
.blob { position:absolute;border-radius:50%;filter:blur(80px);pointer-events:none; }
.blob-tl { width:420px;height:420px;background:rgba(180,100,230,.28);top:-100px;left:-100px; }
.blob-br  { width:380px;height:380px;background:rgba(100,80,220,.22);bottom:-80px;right:-80px; }
.scroll-down-hint {
  position:absolute;bottom:1.5rem;left:50%;transform:translateX(-50%);
  color:var(--purple);font-size:1.4rem;animation:bounceY 1.8s infinite;opacity:.6;
}
@keyframes bounceY {
  0%,100%{transform:translateX(-50%) translateY(0);}
  50%{transform:translateX(-50%) translateY(8px);}
}

/* ====== MEMBER CARD ====== */
.member-card {
  position: relative;
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  transition: transform .3s;
  flex-shrink: 0;
}
.member-card:hover { transform: translateY(-8px); }

.member-card--minister .member-card__img-wrap { width:220px; height:280px; }
.member-card__img-wrap {
  width: 180px;
  height: 230px;
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(74,30,138,.15);
  outline: 3px solid rgba(74,30,138,.15);
  outline-offset: -3px;
}

/* ── Logo kementerian — background hover ── */
.member-card__ministry-bg {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 20px;
  opacity: 0;
  transition: opacity .4s ease;
  z-index: 1;
  background: rgba(247,244,255,.6);
  border-radius: 16px;
}
.member-card:hover .member-card__ministry-bg { opacity: .8; }

.member-card__img {
  width:100%;height:100%;
  object-fit:cover;object-position:top center;
  display:block;
  transition:transform .4s cubic-bezier(.4,0,.2,1);
  position: relative;
  z-index: 2;
}
.member-card:hover .member-card__img { transform: scale(1.06); }

.member-card__placeholder {
  width:100%;height:100%;
  background:var(--purple-pale);
  display:flex;align-items:center;justify-content:center;
  font-size:4rem;color:var(--purple);opacity:.4;
  position: relative;
  z-index: 2;
}

/* Overlay hover (staff & kepala) */
.member-card__overlay--show {
  position:absolute;inset:0;
  display:flex;flex-direction:column;
  align-items:center;justify-content:flex-end;
  padding-bottom:1rem;
  background:linear-gradient(to top,rgba(74,30,138,.82) 0%,transparent 55%);
  opacity:0;
  transition:opacity .3s ease;
  border-radius:16px;
  z-index: 3;
}
.member-card:hover .member-card__overlay--show { opacity: 1; }

/* Overlay menteri — selalu hidden */
.member-card__overlay--hidden {
  position:absolute;inset:0;
  display:flex;flex-direction:column;
  align-items:center;justify-content:flex-end;
  padding-bottom:1rem;
  background:linear-gradient(to top,rgba(74,30,138,.82) 0%,transparent 55%);
  opacity:0 !important;
  pointer-events:none;
  border-radius:16px;
  z-index: 3;
}

.member-card__overlay-name {
  color:#fff;font-weight:700;font-size:.82rem;
  text-align:center;line-height:1.3;padding:0 .5rem;
  text-shadow:0 1px 4px rgba(0,0,0,.4);
}
.member-card__overlay-role { color:rgba(255,255,255,.8);font-size:.72rem;text-align:center; }

/* Label menteri selalu tampil */
.member-card__label--always {
  margin-top:-28px;position:relative;z-index:3;
  background:rgba(255,255,255,.92);backdrop-filter:blur(6px);
  border-radius:12px;padding:.5rem .9rem;
  box-shadow:0 4px 16px rgba(74,30,138,.15);
  border:1px solid rgba(255,255,255,.9);
  text-align:center;min-width:160px;
}
.member-card--minister .member-card__label--always { min-width:200px; }
.member-card__name { display:block;font-size:.82rem;font-weight:700;color:var(--purple-deep);line-height:1.3; }
.member-card__role { display:block;font-size:.72rem;color:var(--purple); }

/* ══════════════════════════════════════════════
   MEMBER CAROUSEL
══════════════════════════════════════════════ */
.member-carousel-outer { position: relative; }
.member-scroll-wrap { overflow: visible; }
.member-scroll-inner {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 24px;
  align-items: flex-start;
}
.member-fade-right { display: none; }

@media (max-width: 767px) {
  .member-scroll-wrap {
    overflow-x: auto;
    overflow-y: visible;
    scrollbar-width: none;
  }
  .member-scroll-wrap::-webkit-scrollbar { display: none; }
  .member-scroll-inner {
    flex-wrap: nowrap;
    justify-content: flex-start;
    gap: 14px;
    padding: .5rem 1rem 1.25rem 1rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }
  .member-card { scroll-snap-align: start; }
  .member-fade-right {
    display: block;
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 48px;
    background: linear-gradient(to left, rgba(255,255,255,.95), transparent);
    pointer-events: none;
  }
  .member-card__img-wrap        { width: 140px; height: 180px; }
  .member-card--minister .member-card__img-wrap { width: 160px; height: 205px; }
}
</style>
@endsection  