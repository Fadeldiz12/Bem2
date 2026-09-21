@extends('layouts.admin')
@section('title','Manajemen Asset')
@section('content')

{{-- Card Upload --}}
<div class="card mb-3">
  <div class="card-header"><i class="bi bi-cloud-upload me-2"></i>Upload Asset Baru</div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.asset.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3 align-items-end">
        {{-- col-12 col-md-3: full width di mobile, 3/12 di desktop --}}
        <div class="col-12 col-md-3">
          <label class="form-label fw-semibold">Judul</label>
          <input type="text" name="title" class="form-control" placeholder="Opsional">
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label fw-semibold">Kategori</label>
          <select name="category" class="form-select" required>
            @foreach(['logo_kabinet'=>'Logo Kabinet','logo_kementerian'=>'Logo Kementerian','foto_pengurus'=>'Foto Pengurus','banner'=>'Banner Website','dokumentasi'=>'Dokumentasi Kegiatan','lainnya'=>'Lainnya'] as $k=>$v)
              <option value="{{ $k }}">{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-md-4">
          <label class="form-label fw-semibold">File</label>
          <input type="file" name="file_path" class="form-control" required>
        </div>
        {{-- Tombol: col-12 col-md-2, w-100 di mobile, auto di desktop --}}
        <div class="col-12 col-md-2">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-upload me-1"></i>Upload
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Card Daftar Asset --}}
<div class="card">
  <div class="card-header">
    <span><i class="bi bi-images me-2"></i>Daftar Asset</span>
    {{-- Filter kategori --}}
    <form method="GET" style="max-width:200px">
      <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">Semua</option>
        @foreach(['logo_kabinet','logo_kementerian','foto_pengurus','banner','dokumentasi','lainnya'] as $c)
          <option value="{{ $c }}" {{ $category===$c ? 'selected' : '' }}>
            {{ \App\Models\Asset::categoryLabel($c) }}
          </option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="card-body">
    <div class="row g-3">
      @forelse($assets as $a)
      {{-- col-6 col-md-3 col-lg-2: mobile 2 kolom, tablet 4 kolom, desktop 6 kolom --}}
      <div class="col-6 col-md-3 col-lg-2">
        <div class="border rounded-3 p-2 text-center h-100">
          @if(in_array(strtolower($a->file_type ?? ''), ['jpg','jpeg','png','webp','gif']))
            <img src="{{ asset('storage/'.$a->file_path) }}"
                 class="mb-2 rounded"
                 style="height:80px;width:100%;object-fit:cover"
                 loading="lazy">
          @else
            <div class="mb-2 d-flex align-items-center justify-content-center bg-light rounded"
                 style="height:80px">
              <i class="bi bi-file-earmark fs-2 text-muted"></i>
            </div>
          @endif
          <small class="d-block fw-semibold text-truncate">{{ $a->title ?: 'Untitled' }}</small>
          <small class="text-muted d-block" style="font-size:.7rem">
            {{ \App\Models\Asset::categoryLabel($a->category) }}
          </small>
          <form method="POST" action="{{ route('admin.asset.destroy', $a->id) }}"
                onsubmit="return confirm('Hapus asset ini?')" class="mt-1">
            @csrf
            <button class="btn btn-sm btn-outline-danger w-100">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
      @empty
        <div class="col-12">
          <p class="text-center text-muted py-4">Belum ada asset.</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

@endsection