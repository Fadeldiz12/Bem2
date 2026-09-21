@extends('layouts.admin')
@section('title','Jadwal Peminjaman Tempat')
@section('content')

<div class="card">
  <div class="card-header">
    <span><i class="bi bi-calendar-event me-2"></i>Jadwal Peminjaman Tempat</span>
    <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Jadwal
    </a>
  </div>

  {{-- table-responsive: tabel bisa di-scroll horizontal di mobile --}}
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Kegiatan</th>
          {{-- Kolom Peminjam disembunyikan di mobile, tampil ab sm --}}
          <th class="d-none d-sm-table-cell">Peminjam</th>
          <th>Tanggal</th>
          {{-- Kolom Waktu & Tempat disembunyikan di mobile --}}
          <th class="d-none d-md-table-cell">Waktu</th>
          <th class="d-none d-md-table-cell">Tempat</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @php
          $sc = ['menunggu'=>'bg-warning','disetujui'=>'bg-success','ditolak'=>'bg-danger'];
          $sl = ['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'];
        @endphp

        @forelse($activities as $i => $a)
        <tr>
          <td>{{ $i+1 }}</td>
          <td><strong>{{ $a->title }}</strong></td>
          <td class="d-none d-sm-table-cell"><small>{{ $a->borrower_name ?? '-' }}</small></td>
          <td><small>{{ $a->activity_date->format('d M Y') }}</small></td>
          <td class="d-none d-md-table-cell">
            <small>{{ $a->start_time ? substr($a->start_time,0,5).' - '.substr($a->end_time,0,5) : '-' }}</small>
          </td>
          <td class="d-none d-md-table-cell"><small>{{ $a->location ?? '-' }}</small></td>
          <td>
            <span class="badge {{ $sc[$a->status] ?? 'bg-secondary' }}">
              {{ $sl[$a->status] ?? $a->status }}
            </span>
          </td>
          <td>
            {{-- flex-wrap agar tombol tidak overflow di layar kecil --}}
            <div class="d-flex flex-wrap gap-1">
              @if($a->status === 'menunggu')
                <form method="POST" action="{{ route('admin.kegiatan.approve', $a->id) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-success" title="Setujui">
                    <i class="bi bi-check-lg"></i>
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.kegiatan.reject', $a->id) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger" title="Tolak">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </form>
              @endif
              <a href="{{ route('admin.kegiatan.edit', $a->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.kegiatan.destroy', $a->id) }}"
                    onsubmit="return confirm('Hapus jadwal ini?')">
                @csrf
                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-4">Belum ada jadwal.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection