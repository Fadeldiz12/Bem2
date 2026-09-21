@extends('layouts.admin')
@section('title', $ministry ? 'Edit Kementerian' : 'Tambah Kementerian')
@section('content')
<div class="card" style="max-width:760px">
  <div class="card-header"><i class="bi bi-building me-2"></i>{{ $ministry ? 'Edit Kementerian' : 'Tambah Kementerian' }}</div>
  <div class="card-body">
    <form method="POST" action="{{ $ministry ? route('admin.kementerian.update', $ministry->id) : route('admin.kementerian.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tahun Kepengurusan *</label>
          <select name="management_year_id" class="form-select" required>
            @foreach($years as $y)<option value="{{ $y->id }}" {{ old('management_year_id', $ministry?->management_year_id) == $y->id ? 'selected' : '' }}>{{ $y->year_label }}</option>@endforeach
          </select>
        </div>
        <div class="col-md-6"><label class="form-label fw-semibold">Urutan</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $ministry?->sort_order ?? 0) }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Nama Kementerian *</label><input type="text" name="name" class="form-control" value="{{ old('name', $ministry?->name) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Alias</label><input type="text" name="alias" class="form-control" value="{{ old('alias', $ministry?->alias) }}"></div>
        <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label><textarea name="description" class="form-control" rows="3">{{ old('description', $ministry?->description) }}</textarea></div>
        <div class="col-12"><label class="form-label fw-semibold">Tugas Pokok dan Fungsi</label><textarea name="tugas_pokok" class="form-control" rows="3">{{ old('tugas_pokok', $ministry?->tugas_pokok) }}</textarea></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Nomor WhatsApp</label><input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $ministry?->whatsapp_number) }}" placeholder="6281234567890"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Label Tombol WA</label><input type="text" name="whatsapp_label" class="form-control" value="{{ old('whatsapp_label', $ministry?->whatsapp_label) }}"></div>
        <div class="col-12">
          <label class="form-label fw-semibold">Logo Kementerian <small class="text-muted">(berbeda tiap angkatan)</small></label>
          <input type="file" name="logo_path" class="form-control" accept="image/*">
          @if($ministry?->logo_path)<img src="{{ asset('storage/'.$ministry->logo_path) }}" class="mt-2 rounded" style="height:70px">@endif
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        <a href="{{ route('admin.kementerian.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
