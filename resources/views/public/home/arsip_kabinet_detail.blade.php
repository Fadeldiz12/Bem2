@extends('layouts.public')
@section('title', 'Arsip '.$archived_year->cabinet_name.' ('.$archived_year->year_label.')')
@section('meta_description', 'Arsip '.$archived_year->cabinet_name.' BEM Politeknik Negeri Medan periode '.$archived_year->year_label.'.'.($archived_year->visi ? ' Visi: '.$archived_year->visi : ''))
@section('content')

{{-- ── Header Kabinet ── --}}
<section class="container py-5 text-center">
  <a href="{{ route('arsip') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-4">← Kembali ke Arsip</a>
  @if($archived_year->logo_path)
    <br><img src="{{ asset('storage/'.$archived_year->logo_path) }}" style="height:140px" class="mb-3" alt="Logo {{ $archived_year->cabinet_name }}">
  @endif
  <h1 class="h2 font-serif fw-bold" style="color:var(--purple-deep)">{{ $archived_year->cabinet_name }}</h1>
  <p class="text-muted">{{ $archived_year->year_label }} · {{ $archived_year->start_date->format('d M Y') }} - {{ $archived_year->end_date->format('d M Y') }}</p>
</section>

{{-- ── Presma & Wapresma ── --}}
@if($archived_year->presma_name || $archived_year->wapresma_name)
<section class="container pb-5 text-center">
  <div class="row g-4 justify-content-center">
    @if($archived_year->presma_name)
    <div class="col-6 col-md-2">
      @if($archived_year->presma_photo)
        <img src="{{ asset('storage/'.$archived_year->presma_photo) }}" class="rounded-3 mb-2" style="width:100%;aspect-ratio:3/4;object-fit:cover" loading="lazy" alt="{{ $archived_year->presma_name }}">
      @endif
      <strong class="d-block">{{ $archived_year->presma_name }}</strong>
      <small class="text-muted">Presiden Mahasiswa</small>
    </div>
    @endif
    @if($archived_year->wapresma_name)
    <div class="col-6 col-md-2">
      @if($archived_year->wapresma_photo)
        <img src="{{ asset('storage/'.$archived_year->wapresma_photo) }}" class="rounded-3 mb-2" style="width:100%;aspect-ratio:3/4;object-fit:cover" loading="lazy" alt="{{ $archived_year->wapresma_name }}">
      @endif
      <strong class="d-block">{{ $archived_year->wapresma_name }}</strong>
      <small class="text-muted">Wakil Presiden Mahasiswa</small>
    </div>
    @endif
  </div>
</section>
@endif

{{-- ── Visi & Misi ── --}}
<section class="container pb-5">
  @if($archived_year->visi)
    <h6 class="text-uppercase fw-bold" style="color:var(--purple)">Visi</h6>
    <p>{{ $archived_year->visi }}</p>
  @endif
  @if(count($archived_misi))
    <h6 class="text-uppercase fw-bold mt-3" style="color:var(--purple)">Misi</h6>
    <ol>@foreach($archived_misi as $m)<li class="mb-2">{{ $m }}</li>@endforeach</ol>
  @endif
</section>

{{-- ── Kementerian + Menteri + Staff ── --}}
<section class="container pb-5">
  <h5 class="fw-bold mb-5 text-center" style="color:var(--purple-deep)">Kementerian</h5>

  @forelse($archived_ministries as $ministry)
  <div class="arsip-ministry-block mb-5">

    {{-- Header kementerian --}}
    <div class="arsip-ministry-header mb-4">
      <div class="arsip-ministry-logo-wrap">
        @if($ministry->logo_path)
          <img src="{{ asset('storage/'.$ministry->logo_path) }}" class="arsip-ministry-logo" alt="{{ $ministry->name }}">
        @else
          <div class="arsip-ministry-logo-empty"><i class="bi bi-building"></i></div>
        @endif
      </div>
      <div>
        <h6 class="fw-bold mb-0" style="color:var(--purple-deep)">{{ $ministry->name }}</h6>
        @if($ministry->alias && $ministry->alias !== $ministry->name)
          <small class="text-muted">{{ $ministry->alias }}</small>
        @endif
      </div>
    </div>

    @php
      $menteri = $ministry->members->where('role', 'menteri')->first();
      $kadep   = $ministry->members->where('is_head', true)->where('role', '!=', 'menteri');
      $staf    = $ministry->members->where('is_head', false)->where('role', '!=', 'menteri');
    @endphp

    {{-- Menteri --}}
    @if($menteri)
    <div class="mb-4">
      <p class="arsip-section-label">Menteri</p>
      <div class="d-flex justify-content-center justify-content-md-start">
        <div class="arsip-person-card arsip-person-card--minister">
          @if($menteri->photo_path)
            <img src="{{ asset('storage/'.$menteri->photo_path) }}" class="arsip-person-img" alt="{{ $menteri->full_name }}">
          @else
            <div class="arsip-person-empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="arsip-person-label">
            <span class="arsip-person-name">{{ $menteri->full_name }}</span>
            <span class="arsip-person-role">{{ $menteri->position }}</span>
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- Kepala Departemen --}}
    @if($kadep->isNotEmpty())
    <div class="mb-4">
      <p class="arsip-section-label">Kepala Departemen</p>
      <div class="arsip-person-grid">
        @foreach($kadep as $p)
        <div class="arsip-person-card">
          @if($p->photo_path)
            <img src="{{ asset('storage/'.$p->photo_path) }}" class="arsip-person-img" alt="{{ $p->full_name }}">
          @else
            <div class="arsip-person-empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="arsip-person-label">
            <span class="arsip-person-name">{{ $p->full_name }}</span>
            <span class="arsip-person-role">{{ $p->position }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Staf --}}
    @if($staf->isNotEmpty())
    <div class="mb-2">
      <p class="arsip-section-label">Staf</p>
      <div class="arsip-person-grid arsip-person-grid--sm">
        @foreach($staf as $p)
        <div class="arsip-person-card arsip-person-card--sm">
          @if($p->photo_path)
            <img src="{{ asset('storage/'.$p->photo_path) }}" class="arsip-person-img" alt="{{ $p->full_name }}">
          @else
            <div class="arsip-person-empty"><i class="bi bi-person-fill"></i></div>
          @endif
          <div class="arsip-person-label">
            <span class="arsip-person-name">{{ $p->full_name }}</span>
            <span class="arsip-person-role">{{ $p->position }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Tidak ada anggota --}}
    @if($ministry->members->isEmpty())
      <p class="text-muted small">Tidak ada data anggota.</p>
    @endif

  </div>

  {{-- Divider antar kementerian --}}
  @if(!$loop->last)
    <hr style="border-color:rgba(74,30,138,.12);margin-bottom:3rem">
  @endif

  @empty
  <p class="text-center text-muted">Tidak ada data kementerian.</p>
  @endforelse
</section>

@endsection
@section('scripts')
<style>
/* ── Ministry Block ── */
.arsip-ministry-block {
  background: #fff;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(74,30,138,.06);
  border: 1px solid rgba(74,30,138,.08);
}

/* ── Ministry Header ── */
.arsip-ministry-header {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.arsip-ministry-logo-wrap {
  width: 56px; height: 56px;
  flex-shrink: 0;
}
.arsip-ministry-logo {
  width: 56px; height: 56px;
  object-fit: contain;
}
.arsip-ministry-logo-empty {
  width: 56px; height: 56px;
  background: var(--purple-pale);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  color: var(--purple); font-size: 1.5rem; opacity: .5;
}

/* ── Section Label ── */
.arsip-section-label {
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--purple);
  margin-bottom: .85rem;
  display: flex;
  align-items: center;
  gap: .75rem;
}
.arsip-section-label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, rgba(74,30,138,.2), transparent);
}

/* ── Person Grid ── */
.arsip-person-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

/* ── Person Card ── */
.arsip-person-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 130px;
  flex-shrink: 0;
}
.arsip-person-card--minister { width: 150px; }
.arsip-person-card--sm       { width: 110px; }

.arsip-person-img {
  width: 100%;
  aspect-ratio: 3/4;
  object-fit: cover;
  object-position: top;
  border-radius: 14px;
  box-shadow: 0 4px 16px rgba(74,30,138,.12);
  outline: 2px solid rgba(74,30,138,.1);
  outline-offset: -2px;
}
.arsip-person-card--minister .arsip-person-img { border-radius: 16px; }
.arsip-person-card--sm       .arsip-person-img { border-radius: 12px; }

.arsip-person-empty {
  width: 100%;
  aspect-ratio: 3/4;
  background: var(--purple-pale);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 2.2rem; color: var(--purple); opacity: .4;
}
.arsip-person-card--sm .arsip-person-empty { font-size: 1.8rem; border-radius: 12px; }

.arsip-person-label {
  margin-top: -16px;
  position: relative;
  z-index: 2;
  background: rgba(255,255,255,.95);
  backdrop-filter: blur(4px);
  border-radius: 8px;
  padding: .3rem .5rem;
  box-shadow: 0 2px 10px rgba(74,30,138,.1);
  border: 1px solid rgba(255,255,255,.9);
  text-align: center;
  width: calc(100% - 8px);
}
.arsip-person-name { display:block;font-size:.7rem;font-weight:700;color:var(--purple-deep);line-height:1.3; }
.arsip-person-role { display:block;font-size:.62rem;color:var(--purple); }

/* ── Mobile: person grid scroll ── */
@media (max-width: 767px) {
  .arsip-ministry-block { padding: 1.25rem; }

  /* Grid anggota jadi horizontal scroll di mobile */
  .arsip-person-grid {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: visible;
    scrollbar-width: none;
    padding-bottom: 1rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    margin: 0 -1.25rem; /* full bleed */
    padding-left: 1.25rem;
    padding-right: 1.25rem;
    justify-content: center;
  }
  .arsip-person-grid::-webkit-scrollbar { display: none; }
  .arsip-person-card { scroll-snap-align: start; }

  /* Ukuran card lebih kecil di mobile */
  .arsip-person-card          { width: 100px; }
  .arsip-person-card--minister{ width: 115px; }
  .arsip-person-card--sm      { width: 88px;  }
}
</style>
@endsection