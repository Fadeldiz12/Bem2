@extends('layouts.admin')
@section('title', $format ? 'Edit Format Surat' : 'Tambah Format Surat')
@section('content')
<div class="card" style="max-width:700px">
  <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>{{ $format ? 'Edit' : 'Tambah' }} Format Surat</div>
  <div class="card-body">
    <form method="POST" action="{{ $format ? route('admin.letter.update', $format->id) : route('admin.letter.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-8"><label class="form-label fw-semibold">Judul *</label><input type="text" name="title" class="form-control" value="{{ old('title', $format?->title) }}" required></div>
        <div class="col-md-4"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $format?->sort_order ?? 0) }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Icon (Bootstrap Icons)</label><input type="text" name="icon_type" class="form-control" value="{{ old('icon_type', $format?->icon_type) }}" placeholder="envelope, clipboard, dll"></div>
        @if($format)<div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="is_active" class="form-select"><option value="1" {{ $format->is_active ? 'selected' : '' }}>Aktif</option><option value="0" {{ !$format->is_active ? 'selected' : '' }}>Nonaktif</option></select></div>@endif
        <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label><textarea name="description" class="form-control" rows="2">{{ old('description', $format?->description) }}</textarea></div>
        <div class="col-md-6"><label class="form-label fw-semibold">File HMPS {{ $format ? '' : '' }}</label><input type="file" name="file_path_hmps" class="form-control">@if($format?->file_path_hmps)<small class="text-muted d-block">File HMPS saat ini: {{ $format->file_path_hmps }}</small>@endif</div>
        <div class="col-md-6"><label class="form-label fw-semibold">File UKM {{ $format ? '' : '' }}</label><input type="file" name="file_path_ukm" class="form-control">@if($format?->file_path_ukm)<small class="text-muted d-block">File UKM saat ini: {{ $format->file_path_ukm }}</small>@endif</div>
        <div class="col-12"><label class="form-label fw-semibold">File Umum (fallback)</label><input type="file" name="file_path" class="form-control">@if($format?->file_path && $format->file_path !== '#')<small class="text-muted d-block">File umum saat ini: {{ $format->file_path }}</small>@endif</div>
      </div>
      <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="{{ route('admin.letter.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
  </div>
</div>
@endsection
