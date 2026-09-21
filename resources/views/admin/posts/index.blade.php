@extends('layouts.admin')
@section('title','Berita & Pengumuman')
@section('content')

<div class="card">
  <div class="card-header">
    <span><i class="bi bi-newspaper me-2"></i>Berita & Pengumuman</span>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Post
    </a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Judul</th>
          <th class="d-none d-sm-table-cell">Kategori</th>
          <th class="d-none d-md-table-cell">Penulis</th>
          <th>Status</th>
          <th class="d-none d-md-table-cell">Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($posts as $i => $p)
        <tr>
          <td>{{ $i+1 }}</td>
          <td><strong>{{ $p->title }}</strong></td>
          <td class="d-none d-sm-table-cell">
            <span class="badge bg-light text-dark">{{ $p->category }}</span>
          </td>
          <td class="d-none d-md-table-cell">
            <small>{{ $p->author?->name }}</small>
          </td>
          <td>
            <span class="badge {{ $p->status==='published' ? 'bg-success' : ($p->status==='draft' ? 'bg-secondary' : 'bg-warning') }}">
              {{ $p->status }}
            </span>
          </td>
          <td class="d-none d-md-table-cell">
            <small>{{ $p->created_at->format('d M Y') }}</small>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.berita.edit', $p->id) }}"
                 class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.berita.destroy', $p->id) }}"
                    onsubmit="return confirm('Hapus post ini?')">
                @csrf
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">Belum ada post.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection