@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

{{-- Baris 1: 4 stat cards — col-6 col-md-3 → mobile 2 kolom, desktop 4 kolom --}}
<div class="row g-3 mb-4">
  @foreach([['Jumlah Kabinet',$total_cabinets,'archive'],['Jumlah Kementerian',$total_ministries,'building'],['Jumlah Departemen',$total_departments,'diagram-3'],['Jumlah Pengurus',$total_members,'people']] as [$label,$val,$icon])
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <p class="mb-1 opacity-75 small">{{ $label }}</p>
          <h3 class="mb-0 fw-bold">{{ $val }}</h3>
        </div>
        <i class="bi bi-{{ $icon }} fs-2 opacity-50"></i>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- Baris 2: 3 stat cards — col-12 col-sm-6 col-md-4 → mobile full, sm 2 kolom, desktop 3 kolom --}}
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-md-4">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <p class="mb-1 opacity-75 small">Jumlah Program Kerja</p>
          <h3 class="mb-0 fw-bold">{{ $total_programs }}</h3>
        </div>
        <i class="bi bi-kanban fs-2 opacity-50"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <p class="mb-1 opacity-75 small">Jumlah Peminjaman Tempat</p>
          <h3 class="mb-0 fw-bold">{{ $total_bookings }}</h3>
        </div>
        <i class="bi bi-calendar-event fs-2 opacity-50"></i>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-12 col-md-4">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <p class="mb-1 opacity-75 small">Kabinet Aktif</p>
          <h5 class="mb-0 fw-bold">{{ $active_year->cabinet_name ?? '-' }}</h5>
          <small class="opacity-75">{{ $active_year->year_label ?? '' }}</small>
        </div>
        <i class="bi bi-calendar3 fs-2 opacity-50"></i>
      </div>
    </div>
  </div>
</div>

{{-- Baris 3: 2 card list — col-12 col-md-6 → mobile full width, desktop 50/50 --}}
<div class="row g-3">
  <div class="col-12 col-md-6">
    <div class="card h-100">
      {{-- card-header: flex-wrap agar tombol tidak bertabrakan di mobile --}}
      <div class="card-header" style="flex-wrap:wrap; gap:.5rem;">
        <span><i class="bi bi-calendar-event me-2"></i>Peminjaman Mendatang (Disetujui)</span>
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        @forelse($upcoming as $a)
          <div class="list-group-item p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start gap-2">
              {{-- min-width:0 agar teks bisa terpotong dengan benar --}}
              <div style="min-width:0">
                <div class="fw-semibold small text-truncate">{{ $a->title }}</div>
                <div class="text-muted" style="font-size:.78rem">
                  {{ $a->activity_date->format('d M Y') }}{{ $a->location ? ' · '.$a->location : '' }}
                </div>
              </div>
              <span class="badge bg-success rounded-pill flex-shrink-0">Disetujui</span>
            </div>
          </div>
        @empty
          <p class="text-muted text-center py-4">Tidak ada peminjaman mendatang.</p>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6">
    <div class="card h-100">
      <div class="card-header" style="flex-wrap:wrap; gap:.5rem;">
        <span><i class="bi bi-hourglass-split me-2"></i>Menunggu Persetujuan</span>
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-sm btn-outline-primary">Kelola</a>
      </div>
      <div class="card-body p-0">
        @forelse($pending_bookings as $p)
          <div class="list-group-item p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div style="min-width:0">
                <div class="fw-semibold small text-truncate">{{ $p->title }}</div>
                <div class="text-muted" style="font-size:.78rem">
                  {{ $p->borrower_name ?? '-' }} · {{ $p->activity_date->format('d M Y') }}
                </div>
              </div>
              <span class="badge bg-warning rounded-pill flex-shrink-0">Menunggu</span>
            </div>
          </div>
        @empty
          <p class="text-muted text-center py-4">Tidak ada permintaan menunggu.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection