@extends('layouts.public')
@section('title', trim('Pengurus '.($year?->cabinet_name ?? '')))
@section('meta_description','Daftar pengurus '.($year?->cabinet_name ?? 'BEM Polmed').' BEM Politeknik Negeri Medan: presiden dan wakil presiden mahasiswa, menteri, kepala departemen, serta staf.')
@section('content')
<section class="container py-5">
  <div class="text-center mb-5"><h1 class="page-pill">Pengurus</h1></div>

  {{-- ═══════════════════════════════════════════
       Presma & Wapresma
  ════════════════════════════════════════════ --}}
  @if($year?->presma_name || $year?->wapresma_name)
  <div class="presma-row mb-5">
    @foreach([
      ['name'=>$year->presma_name,  'photo'=>$year->presma_photo,  'pos'=>'Presiden Mahasiswa'],
      ['name'=>$year->wapresma_name,'photo'=>$year->wapresma_photo,'pos'=>'Wakil Presiden Mahasiswa']
    ] as $p)
      @if($p['name'])
      <div class="pcard pcard--lg">
        <div class="pcard__wrap">
          @if($p['photo'])
            <img src="{{ asset('storage/'.$p['photo']) }}" class="pcard__img" loading="lazy" alt="{{ $p['name'] }}">
          @else
            <div class="pcard__empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="pcard__overlay pcard__overlay--hidden">
            <span class="pcard__overlay-name">{{ $p['name'] }}</span>
            <span class="pcard__overlay-role">{{ $p['pos'] }}</span>
          </div>
        </div>
        <div class="pcard__label">
          <span class="pcard__name">{{ $p['name'] }}</span>
          <span class="pcard__role">{{ $p['pos'] }}</span>
        </div>
      </div>
      @endif
    @endforeach
  </div>
  @endif

  {{-- ═══════════════════════════════════════════
       Filter berdasarkan kolom `role` dan `is_head`
       Menteri  → role = 'menteri'
       Kadep    → is_head = true (dan bukan menteri)
       Staf     → is_head = false (dan bukan menteri)
  ════════════════════════════════════════════ --}}
  @php
    $menteri = $pengurus->where('role', 'menteri');
    $kadep   = $pengurus->where('is_head', true)->where('role', '!=', 'menteri');
    $staf    = $pengurus->where('is_head', false)->where('role', '!=', 'menteri');
  @endphp

  <h5 class="fw-bold text-center mb-4" style="color:var(--purple-deep)">
    Menteri, Kepala Departemen & Staf
  </h5>

  {{-- ─────────────────────────────────────────
       Baris 1: Menteri
  ───────────────────────────────────────────── --}}
  @if($menteri->isNotEmpty())
  <div class="section-group mb-5">
    <div class="section-label">Menteri</div>
    <div class="card-row card-row--md">
      @foreach($menteri as $p)
      <div class="pcard pcard--md">
        <div class="pcard__wrap">
          @if($p->ministry?->logo_path)
            <img src="{{ asset('storage/'.$p->ministry->logo_path) }}" class="pcard__ministry-bg" loading="lazy" alt="">
          @endif
          @if($p->photo_path)
            <img src="{{ asset('storage/'.$p->photo_path) }}" class="pcard__img" loading="lazy" alt="{{ $p->full_name }}">
          @else
            <div class="pcard__empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="pcard__overlay pcard__overlay--hidden">
            <span class="pcard__overlay-name">{{ $p->full_name }}</span>
            <span class="pcard__overlay-role">{{ $p->ministry?->name }}</span>
          </div>
        </div>
        <div class="pcard__label">
          <span class="pcard__name">{{ $p->full_name }}</span>
          <span class="pcard__role">{{ $p->position }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- ─────────────────────────────────────────
       Baris 2: Kepala Departemen
  ───────────────────────────────────────────── --}}
  @if($kadep->isNotEmpty())
  <div class="section-group mb-5">
    <div class="section-label">Kepala Departemen</div>
    <div class="card-row card-row--md">
      @foreach($kadep as $p)
      <div class="pcard pcard--md">
        <div class="pcard__wrap">
          @if($p->ministry?->logo_path)
            <img src="{{ asset('storage/'.$p->ministry->logo_path) }}" class="pcard__ministry-bg" loading="lazy" alt="">
          @endif
          @if($p->photo_path)
            <img src="{{ asset('storage/'.$p->photo_path) }}" class="pcard__img" loading="lazy" alt="{{ $p->full_name }}">
          @else
            <div class="pcard__empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="pcard__overlay pcard__overlay--hidden">
            <span class="pcard__overlay-name">{{ $p->full_name }}</span>
            <span class="pcard__overlay-role">{{ $p->department?->name ?: $p->ministry?->name }}</span>
          </div>
        </div>
        <div class="pcard__label">
          <span class="pcard__name">{{ $p->full_name }}</span>
          <span class="pcard__role">{{ $p->position }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- ─────────────────────────────────────────
       Baris 3: Staf
  ───────────────────────────────────────────── --}}
  @if($staf->isNotEmpty())
  <div class="section-group">
    <div class="section-label">Staf</div>
    <div class="card-row card-row--sm">
      @foreach($staf as $p)
      <div class="pcard pcard--sm">
        <div class="pcard__wrap">
          @if($p->ministry?->logo_path)
            <img src="{{ asset('storage/'.$p->ministry->logo_path) }}" class="pcard__ministry-bg" loading="lazy" alt="">
          @endif
          @if($p->photo_path)
            <img src="{{ asset('storage/'.$p->photo_path) }}" class="pcard__img" loading="lazy" alt="{{ $p->full_name }}">
          @else
            <div class="pcard__empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="pcard__overlay pcard__overlay--hidden">
            <span class="pcard__overlay-name">{{ $p->full_name }}</span>
            <span class="pcard__overlay-role">{{ $p->department?->name ?: $p->ministry?->name }}</span>
          </div>
        </div>
        <div class="pcard__label">
          <span class="pcard__name">{{ $p->full_name }}</span>
          <span class="pcard__role">{{ $p->position }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Fallback jika $pengurus kosong total --}}
  @if($pengurus->isEmpty())
    <p class="text-center text-muted">Belum ada data pengurus.</p>
  @endif

</section>
@endsection

@section('scripts')
<style>
/* ════════════════════════════════════════════
   PCARD — Base
════════════════════════════════════════════ */
.pcard {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  transition: transform .3s;
  cursor: default;
  flex-shrink: 0; /* penting untuk carousel mobile */
}
.pcard:hover { transform: translateY(-8px); }

/* Sizes */
.pcard--lg .pcard__wrap { width: 200px; height: 260px; }
.pcard--md .pcard__wrap { width: 160px; height: 210px; }
.pcard--sm .pcard__wrap { width: 130px; height: 170px; }
.pcard--sm .pcard__label { min-width: 110px; }
.pcard--sm .pcard__name  { font-size: .72rem; }
.pcard--sm .pcard__role  { font-size: .63rem; }

/* Wrapper foto */
.pcard__wrap {
  position: relative;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(74,30,138,.15);
  outline: 3px solid rgba(74,30,138,.12);
  outline-offset: -3px;
}

/* Logo Kementerian — muncul saat hover */
.pcard__ministry-bg {
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
  border-radius: 18px;
}
.pcard:hover .pcard__ministry-bg { opacity: .8; }

/* Foto */
.pcard__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: top;
  display: block;
  transition: transform .4s;
  position: relative;
  z-index: 2;
}
.pcard:hover .pcard__img { transform: scale(1.07); }

/* Empty state */
.pcard__empty {
  width: 100%;
  height: 100%;
  background: var(--purple-pale);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3.5rem;
  color: var(--purple);
  opacity: .4;
  position: relative;
  z-index: 2;
}

/* Overlay — selalu hidden */
.pcard__overlay--hidden {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
  padding-bottom: .9rem;
  background: linear-gradient(to top, rgba(74,30,138,.88) 0%, transparent 55%);
  opacity: 0 !important;
  pointer-events: none;
  border-radius: 18px;
  z-index: 3;
}
.pcard__overlay-name {
  color: #fff;
  font-weight: 700;
  font-size: .8rem;
  text-align: center;
  padding: 0 .5rem;
  text-shadow: 0 1px 4px rgba(0,0,0,.4);
  line-height: 1.3;
}
.pcard__overlay-role { color: rgba(255,255,255,.75); font-size: .7rem; text-align: center; }

/* Label bawah foto */
.pcard__label {
  margin-top: -22px;
  position: relative;
  z-index: 3;
  background: rgba(255,255,255,.93);
  backdrop-filter: blur(6px);
  border-radius: 12px;
  padding: .45rem .8rem;
  box-shadow: 0 4px 16px rgba(74,30,138,.12);
  border: 1px solid rgba(255,255,255,.9);
  text-align: center;
  min-width: 140px;
}
.pcard--lg .pcard__label { min-width: 180px; }
.pcard__name { display: block; font-size: .78rem; font-weight: 700; color: var(--purple-deep); line-height: 1.3; }
.pcard__role { display: block; font-size: .68rem; color: var(--purple); }

/* ════════════════════════════════════════════
   PRESMA ROW — Desktop: center, Mobile: carousel
════════════════════════════════════════════ */
.presma-row {
  display: flex;
  justify-content: center;
  gap: 3rem;
  flex-wrap: wrap;
}

/* ════════════════════════════════════════════
   CARD ROW — Desktop: flex wrap center
════════════════════════════════════════════ */
.card-row {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1rem;
}

/* ════════════════════════════════════════════
   SECTION GROUP — Divider label bergaris
════════════════════════════════════════════ */
.section-group { position: relative; }

.section-label {
  display: flex;
  align-items: center;
  gap: 1rem;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--purple);
  margin-bottom: 1.5rem;
}
.section-label::before,
.section-label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, transparent, rgba(74,30,138,.25), transparent);
}

/* ════════════════════════════════════════════
   MOBILE CAROUSEL  (max-width: 767px)
   • card-row berubah jadi horizontal scroll
   • section-group dapat fade hint di kanan
════════════════════════════════════════════ */
@media (max-width: 767px) {

  /* Kurangi padding container supaya carousel full-bleed */
  section.container { padding-left: 0 !important; padding-right: 0 !important; }
  .section-label    { padding: 0 1rem; }
  .section-group    { overflow: hidden; } /* clip fade kanan */

  /* Fade hint kanan — tanda bisa diswipe */
  .section-group::after {
    content: '';
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 52px;
    background: linear-gradient(to left, rgba(255,255,255,.95), transparent);
    pointer-events: none;
    z-index: 10;
  }

  /* Carousel scroll */
  .card-row {
    flex-wrap: nowrap;                  /* card tidak turun ke baris baru */
    overflow-x: auto;                   /* scroll horizontal */
    overflow-y: visible;
    justify-content: flex-start;
    gap: .85rem;
    padding: .5rem 1rem 1.5rem 1rem;   /* padding kiri-kanan agar card pertama/terakhir tidak nempel tepi */
    scroll-snap-type: x mandatory;     /* snap tiap card */
    -webkit-overflow-scrolling: touch; /* smooth di iOS */
    scrollbar-width: none;             /* sembunyikan scrollbar Firefox */
  }
  .card-row::-webkit-scrollbar { display: none; } /* sembunyikan scrollbar Chrome/Safari */

  /* Tiap card: tidak mengecil, snap ke kiri */
  .card-row > .pcard {
    scroll-snap-align: start;
  }

  /* Presma: carousel juga di mobile */
  .presma-row {
    flex-wrap: nowrap;
    overflow-x: auto;
    justify-content: flex-start;
    gap: 1rem;
    padding: .5rem 1.5rem 1.5rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
  }
  .presma-row::-webkit-scrollbar { display: none; }
  .presma-row > .pcard { scroll-snap-align: center; }

  /* Sesuaikan ukuran card di mobile agar 2.3 card kelihatan sekaligus */
  .pcard--lg .pcard__wrap { width: 155px; height: 205px; }
  .pcard--md .pcard__wrap { width: 130px; height: 172px; }
  .pcard--sm .pcard__wrap { width: 110px; height: 148px; }

  .pcard--lg .pcard__label { min-width: 135px; }
  .pcard--md .pcard__label { min-width: 115px; }
  .pcard--sm .pcard__label { min-width: 95px;  }

  .pcard--lg .pcard__name { font-size: .74rem; }
  .pcard--md .pcard__name { font-size: .72rem; }
}
</style>
@endsection