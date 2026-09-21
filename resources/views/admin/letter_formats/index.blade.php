@extends('layouts.admin')
@section('title','Format Surat')
@section('content')

<div class="card">
  <div class="card-header">
    <span><i class="bi bi-file-earmark-text me-2"></i>Format Surat</span>
    <a href="{{ route('admin.letter.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Format
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Judul</th>
          <th class="d-none d-md-table-cell">Tipe</th>
          <th class="d-none d-md-table-cell">Download</th>
          <th>Status</th>
          <th class="d-none d-lg-table-cell">Urutan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($formats as $i => $f)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>
            <i class="bi bi-{{ $f->icon_type ?? 'file-text' }} me-2" style="color:#7c3aed"></i>
            <strong>{{ $f->title }}</strong>
          </td>
          <td class="d-none d-md-table-cell">
            <span class="badge bg-light text-dark">{{ $f->file_type ?? '-' }}</span>
          </td>
          <td class="d-none d-md-table-cell">{{ $f->download_count }}x</td>
          <td>
            @if($f->is_active)
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-secondary">Nonaktif</span>
            @endif
          </td>
          <td class="d-none d-lg-table-cell">{{ $f->sort_order }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.letter.edit', $f->id) }}"
                 class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.letter.destroy', $f->id) }}"
                    onsubmit="return confirm('Hapus format surat ini?')">
                @csrf
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">Belum ada format surat.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection