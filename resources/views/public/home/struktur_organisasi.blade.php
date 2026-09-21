@extends('layouts.public')
@section('title','Struktur Organisasi')
@section('content')
<section class="container py-5 text-center">
  <div class="mb-5"><span class="page-pill">Struktur Organisasi</span></div>
  @if(!$year)
    <p class="text-muted">Belum ada kabinet aktif.</p>
  @else
  <div class="org-chart mx-auto" style="max-width:900px">
    @if($year->presma_name)
    <a href="{{ route('pengurus') }}" class="org-node org-level-1 mb-2"><i class="bi bi-person-fill"></i> {{ $year->presma_name }}<small class="d-block">Presiden Mahasiswa</small></a>
    <div class="org-line"></div>
    @endif
    @if($year->wapresma_name)
    <a href="{{ route('pengurus') }}" class="org-node org-level-2 mb-2"><i class="bi bi-person"></i> {{ $year->wapresma_name }}<small class="d-block">Wakil Presiden Mahasiswa</small></a>
    <div class="org-line"></div>
    @endif

    {{-- ── Kementerian + Departemen (digabung dalam satu kartu per kementerian)
         Desktop & Mobile : horizontal scroll carousel supaya rapi walau jumlah
         kementerian/departemen banyak dan tidak seragam.
    ──────────────────────────────────────── --}}
    <p class="org-section-label">Kementerian &amp; Departemen</p>
    <div class="org-scroll-outer">
      <button type="button" class="org-scroll-btn org-scroll-btn--left" id="orgScrollBtnLeft" aria-label="Geser ke kiri" onclick="orgScroll(-1)">
        <i class="bi bi-chevron-left"></i>
      </button>

      <div class="org-scroll-wrap" id="orgScrollWrap">
        <div class="org-scroll-inner">
          @foreach($ministries_with_depts as $m)
          <div class="org-dept-card">
            <a href="{{ route('kementerian.detail', $m->id) }}" class="org-dept-card-header">
              @if($m->logo_path)
                <img src="{{ asset('storage/'.$m->logo_path) }}" class="org-dept-card-logo" alt="{{ $m->alias ?: $m->name }}">
              @else
                <div class="org-dept-card-logo org-dept-card-logo--empty"><i class="bi bi-building"></i></div>
              @endif
              <span class="org-dept-card-name">{{ $m->alias ?: $m->name }}</span>
            </a>

            @if($m->departments->count())
            <div class="org-dept-chips">
              @foreach($m->departments as $d)
              <a href="{{ route('kementerian.detail', $m->id) }}" class="org-dept-chip">{{ $d->name }}</a>
              @endforeach
            </div>
            @else
            <p class="org-dept-chip-empty mb-0">Belum ada departemen</p>
            @endif
          </div>
          @endforeach
        </div>
      </div>
      <button type="button" class="org-scroll-btn org-scroll-btn--right" id="orgScrollBtnRight" aria-label="Geser ke kanan" onclick="orgScroll(1)">
        <i class="bi bi-chevron-right"></i>
      </button>

      {{-- Fade hint kanan, penanda masih bisa di-scroll --}}
      <div class="org-fade-right"></div>
    </div>

  </div>
  @endif
</section>
@endsection
@section('scripts')
<style>
/* ══════════════════════════════════════════════
   ORG SECTION LABEL
══════════════════════════════════════════════ */
.org-section-label {
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--purple);
  display: flex;
  align-items: center;
  gap: 1rem;
  margin: 0 0 1.25rem;
}
.org-section-label::before,
.org-section-label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, transparent, rgba(74,30,138,.2), transparent);
}

/* ══════════════════════════════════════════════
   ORG SCROLL — kementerian + departemen
   Satu baris horizontal, scroll kalau kelebihan,
   berlaku di desktop maupun mobile.
══════════════════════════════════════════════ */
.org-scroll-outer {
  position: relative;
}
.org-scroll-wrap {
  overflow-x: auto;
  overflow-y: visible;
  scrollbar-width: none;
}
.org-scroll-wrap::-webkit-scrollbar { display: none; }

.org-scroll-inner {
  display: flex;
  flex-wrap: nowrap;
  justify-content: flex-start;
  align-items: flex-start;
  gap: 1rem;
  padding: .5rem .25rem 1.25rem .25rem;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
}

/* Fade hint kanan — penanda konten masih berlanjut */
.org-fade-right {
  position: absolute;
  right: 0; top: 0; bottom: 0;
  width: 48px;
  background: linear-gradient(to left, rgba(255,255,255,.95), transparent);
  pointer-events: none;
  border-radius: 0 16px 16px 0;
}

/* ── Tombol panah geser — desktop saja ── */
.org-scroll-btn {
  display: none;
  position: absolute;
  top: 42%;
  transform: translateY(-50%);
  z-index: 5;
  width: 36px;
  height: 36px;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #fff;
  border: 1px solid rgba(74,30,138,.14);
  box-shadow: 0 4px 14px rgba(74,30,138,.2);
  color: var(--purple-deep);
  font-size: .95rem;
  cursor: pointer;
  padding: 0;
  transition: background .2s, color .2s, opacity .2s, visibility .2s;
}
.org-scroll-btn:hover {
  background: var(--purple);
  color: #fff;
}
.org-scroll-btn:disabled {
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
}
.org-scroll-btn--left  { left: 4px; }
.org-scroll-btn--right { right: 4px; }

@media (min-width: 768px) {
  .org-scroll-btn { display: flex; }
}

/* ── Kartu kementerian ── */
.org-dept-card {
  scroll-snap-align: start;
  flex-shrink: 0;
  width: 210px;
  background: #fff;
  border-radius: 16px;
  padding: 1rem;
  text-align: left;
  box-shadow: 0 4px 18px rgba(74,30,138,.08);
  border: 1px solid rgba(74,30,138,.08);
  transition: box-shadow .25s, transform .25s;
}
.org-dept-card:hover {
  box-shadow: 0 10px 28px rgba(74,30,138,.16);
  transform: translateY(-3px);
}

.org-dept-card-header {
  display: flex;
  align-items: center;
  gap: .6rem;
  text-decoration: none;
  padding-bottom: .75rem;
  margin-bottom: .75rem;
  border-bottom: 1px dashed rgba(74,30,138,.15);
}
.org-dept-card-logo {
  width: 34px; height: 34px;
  object-fit: contain;
  flex-shrink: 0;
}
.org-dept-card-logo--empty {
  background: var(--purple-pale);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: var(--purple); font-size: 1rem; opacity: .6;
}
.org-dept-card-name {
  font-weight: 700;
  font-size: .82rem;
  color: var(--purple-deep);
  line-height: 1.25;
}

/* ── Chip departemen di dalam kartu ── */
.org-dept-chips {
  display: flex;
  flex-wrap: wrap;
  gap: .4rem;
}
.org-dept-chip {
  display: inline-block;
  max-width: 100%;
  font-size: .68rem;
  font-weight: 600;
  color: var(--purple-deep);
  background: var(--purple-pale);
  border-radius: 12px;
  padding: .3rem .65rem;
  text-decoration: none;
  white-space: normal;
  overflow-wrap: break-word;
  word-break: break-word;
  line-height: 1.35;
  transition: background .2s, color .2s;
}
.org-dept-chip:hover {
  background: var(--purple);
  color: #fff;
}
.org-dept-chip-empty {
  font-size: .7rem;
  color: #999;
}

/* ══════════════════════════════════════════════
   MOBILE ≤ 767px — kartu sedikit lebih ramping
══════════════════════════════════════════════ */
@media (max-width: 767px) {
  .org-dept-card { width: 178px; padding: .85rem; }
  .org-dept-card-logo,
  .org-dept-card-logo--empty { width: 28px; height: 28px; font-size: .85rem; }
  .org-dept-card-name { font-size: .76rem; }
  .org-dept-chip { font-size: .64rem; padding: .28rem .55rem; }
}
</style>

<script>
(function () {
  var wrap     = document.getElementById('orgScrollWrap');
  var btnLeft  = document.getElementById('orgScrollBtnLeft');
  var btnRight = document.getElementById('orgScrollBtnRight');
  if (!wrap || !btnLeft || !btnRight) return;

  function updateButtons() {
    var maxScroll = wrap.scrollWidth - wrap.clientWidth;
    // Konten tidak overflow sama sekali → sembunyikan kedua panah
    if (maxScroll <= 4) {
      btnLeft.disabled  = true;
      btnRight.disabled = true;
      return;
    }
    btnLeft.disabled  = wrap.scrollLeft <= 4;
    btnRight.disabled = wrap.scrollLeft >= maxScroll - 4;
  }

  window.orgScroll = function (direction) {
    wrap.scrollBy({ left: direction * wrap.clientWidth * 0.85, behavior: 'smooth' });
  };

  wrap.addEventListener('scroll', updateButtons);
  window.addEventListener('resize', updateButtons);
  window.addEventListener('load', updateButtons);
  updateButtons();
})();
</script>
@endsection