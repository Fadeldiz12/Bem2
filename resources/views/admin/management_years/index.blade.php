@extends('layouts.admin')
@section('title','Manajemen Kabinet')
@section('content')

<div class="card">
  <div class="card-header">
    <span><i class="bi bi-archive me-2"></i>Manajemen Kabinet</span>
    <a href="{{ route('admin.kabinet.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Buat Kabinet Baru
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Tahun</th>
          <th>Kabinet</th>
          {{-- Kolom Presma/Wapresma disembunyikan di mobile --}}
          <th class="d-none d-md-table-cell">Presma / Wapresma</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($years as $i => $y)
        <tr>
          <td>{{ $i+1 }}</td>
          <td><strong>{{ $y->year_label }}</strong></td>
          <td>{{ $y->cabinet_name }}</td>
          <td class="d-none d-md-table-cell">
            <small>{{ $y->presma_name ?? '-' }} / {{ $y->wapresma_name ?? '-' }}</small>
          </td>
          <td>
            @if($y->status === 'published')
              <span class="badge bg-success">Aktif</span>
            @elseif($y->status === 'draft')
              <span class="badge bg-secondary">Draft</span>
            @else
              <span class="badge" style="background:#7c3aed">Arsip</span>
            @endif
          </td>
          <td>
            <div class="d-flex flex-wrap gap-1">
              <a href="{{ route('admin.kabinet.edit', $y->id) }}"
                 class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="{{ route('admin.kabinet.preview', $y->id) }}"
                 target="_blank" class="btn btn-sm btn-outline-info">
                <i class="bi bi-eye"></i>
              </a>
              @if($y->status !== 'published')
                <form method="POST" action="{{ route('admin.kabinet.publish', $y->id) }}"
                      onsubmit="return confirm('Publish kabinet ini? Kabinet aktif akan diarsipkan.')">
                  @csrf
                  <button class="btn btn-sm btn-outline-success">
                    <i class="bi bi-check-circle"></i>
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.kabinet.destroy', $y->id) }}"
                      onsubmit="return confirm('Hapus kabinet ini?')">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection