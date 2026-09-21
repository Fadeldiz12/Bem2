@extends('layouts.admin')
@section('title', $program ? 'Edit Program Kerja' : 'Tambah Program Kerja')
@section('content')
<div class="card" style="max-width:700px">
  <div class="card-header"><i class="bi bi-kanban me-2"></i>{{ $program ? 'Edit' : 'Tambah' }} Program Kerja</div>
  <div class="card-body">
    <form method="POST" action="{{ $program ? route('admin.program.update', $program->id) : route('admin.program.store') }}">
      @csrf
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label fw-semibold">Departemen *</label>
          <select name="department_id" class="form-select" required>
            <option value="">-- Pilih Departemen --</option>
            @foreach($departments as $d)
              <option value="{{ $d['id'] }}" {{ old('department_id', $program?->department_id) == $d['id'] ? 'selected' : '' }}>
                {{ $d['label'] }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Nama Program Kerja *</label>
          <input type="text" name="name" class="form-control"
                 value="{{ old('name', $program?->name) }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tanggal Pelaksanaan</label>
          <input type="date" name="execution_date" class="form-control"
                 value="{{ old('execution_date', $program?->execution_date?->format('Y-m-d')) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select">
            @foreach(['akan_dilaksanakan'=>'Akan Dilaksanakan','sedang_berjalan'=>'Sedang Berjalan','selesai'=>'Selesai'] as $k=>$v)
              <option value="{{ $k }}" {{ old('status', $program?->status ?? 'akan_dilaksanakan') === $k ? 'selected' : '' }}>
                {{ $v }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Deskripsi</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $program?->description) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Urutan Tampilan</label>
          <input type="number" name="sort_order" class="form-control" min="0"
                 value="{{ old('sort_order', $program?->sort_order ?? 1) }}">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        <a href="{{ route('admin.program.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection