@extends('layouts.admin')
@section('title', $activity ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('content')
<div class="card" style="max-width:700px">
  <div class="card-header"><i class="bi bi-calendar-event me-2"></i>{{ $activity ? 'Edit Jadwal' : 'Tambah Jadwal' }}</div>
  <div class="card-body">
    <form method="POST" action="{{ $activity ? route('admin.kegiatan.update', $activity->id) : route('admin.kegiatan.store') }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label fw-semibold">Nama Kegiatan *</label><input type="text" name="title" class="form-control" value="{{ old('title', $activity?->title) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Nama Peminjam *</label><input type="text" name="borrower_name" class="form-control" value="{{ old('borrower_name', $activity?->borrower_name) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Tempat *</label><input type="text" name="location" class="form-control" value="{{ old('location', $activity?->location) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal *</label><input type="date" name="activity_date" class="form-control" value="{{ old('activity_date', $activity?->activity_date?->format('Y-m-d')) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Jam Mulai</label><input type="time" name="start_time" class="form-control" value="{{ old('start_time', $activity?->start_time) }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Jam Selesai</label><input type="time" name="end_time" class="form-control" value="{{ old('end_time', $activity?->end_time) }}"></div>
        <div class="col-12"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select">@foreach(['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $k=>$v)<option value="{{ $k }}" {{ old('status', $activity?->status ?? 'menunggu')===$k?'selected':'' }}>{{ $v }}</option>@endforeach</select></div>
        <div class="col-12"><label class="form-label fw-semibold">Deskripsi</label><textarea name="description" class="form-control" rows="3">{{ old('description', $activity?->description) }}</textarea></div>
      </div>
      <div class="alert alert-info small mt-3"><i class="bi bi-info-circle me-1"></i>Sistem otomatis menolak jika tempat, tanggal, dan jam bertabrakan dengan jadwal lain.</div>
      <div class="d-flex gap-2 mt-3"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="{{ route('admin.kegiatan.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
  </div>
</div>
@endsection
