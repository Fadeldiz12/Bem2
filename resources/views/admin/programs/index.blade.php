@extends('layouts.admin')
@section('title','Program Kerja')
@section('content')

<div class="card">
  <div class="card-header">
    <span><i class="bi bi-kanban me-2"></i>Program Kerja</span>
    <a href="{{ route('admin.program.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Program Kerja
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama Program</th>
          <th class="d-none d-md-table-cell">Departemen</th>
          <th class="d-none d-sm-table-cell">Tanggal</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @php
          $sc = ['akan_dilaksanakan'=>'bg-secondary','sedang_berjalan'=>'bg-warning','selesai'=>'bg-success'];
          $sl = ['akan_dilaksanakan'=>'Akan Dilaksanakan','sedang_berjalan'=>'Sedang Berjalan','selesai'=>'Selesai'];
        @endphp
        @forelse($programs as $i => $p)
        <tr>
          <td>{{ $i+1 }}</td>
          <td><strong>{{ $p->name }}</strong></td>
          <td class="d-none d-md-table-cell">
            <small>{{ $p->department?->ministry?->name }} — {{ $p->department?->name }}</small>
          </td>
          <td class="d-none d-sm-table-cell">
            <small>{{ $p->execution_date?->format('d M Y') ?? '-' }}</small>
          </td>
          <td>
            <span class="badge {{ $sc[$p->status] ?? 'bg-secondary' }}">
              {{ $sl[$p->status] ?? $p->status }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.program.edit', $p->id) }}"
                 class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.program.destroy', $p->id) }}"
                    onsubmit="return confirm('Hapus program kerja ini?')">
                @csrf
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">Belum ada program kerja.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection