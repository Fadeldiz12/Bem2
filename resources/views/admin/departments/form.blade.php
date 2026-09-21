@extends('layouts.admin')
@section('title', $department ? 'Edit Departemen' : 'Tambah Departemen')
@section('content')
<div class="card" style="max-width:700px">
  <div class="card-header"><i class="bi bi-diagram-3 me-2"></i>{{ $department ? 'Edit Departemen' : 'Tambah Departemen' }}</div>
  <div class="card-body">
    <form method="POST" action="{{ $department ? route('admin.departemen.update', $department->id) : route('admin.departemen.store') }}">
      @csrf
      <div class="row g-3">

        {{-- Periode / Tahun --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Periode *</label>
          <select id="year_id" class="form-select" required>
            <option value="">-- Pilih Periode --</option>
            @foreach($years as $y)
              <option value="{{ $y->id }}"
                {{ old('year_id', $department?->ministry?->management_year_id ?? $activeYearId) == $y->id ? 'selected' : '' }}>
                {{ $y->year_label }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Menentukan daftar kementerian yang tersedia.</div>
        </div>

        {{-- Kementerian — load via AJAX sesuai periode --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Kementerian *</label>
          <select name="ministry_id" id="ministry_id" class="form-select" required>
            <option value="">-- Pilih Kementerian --</option>
            @foreach($ministries as $m)
              <option value="{{ $m->id }}" {{ old('ministry_id', $department?->ministry_id) == $m->id ? 'selected' : '' }}>
                {{ $m->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-8">
          <label class="form-label fw-semibold">Nama Departemen *</label>
          <input type="text" name="name" class="form-control"
                 value="{{ old('name', $department?->name) }}" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Urutan</label>
          <input type="number" name="sort_order" class="form-control"
                 value="{{ old('sort_order', $department?->sort_order ?? 0) }}">
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Deskripsi</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $department?->description) }}</textarea>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Fungsi / Tugas Pokok</label>
          <textarea name="tugas_pokok" class="form-control" rows="3">{{ old('tugas_pokok', $department?->tugas_pokok) }}</textarea>
        </div>

      </div>

      @if($department)
        <div class="alert alert-info small mt-3">
          <i class="bi bi-info-circle me-1"></i>
          Kepala Departemen dikelola lewat <a href="{{ route('admin.pengurus.index') }}">Pengurus</a>.
          Program Kerja lewat <a href="{{ route('admin.program.index') }}">Program Kerja</a>.
        </div>
      @endif

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        <a href="{{ route('admin.departemen.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
  const yearSelect     = document.getElementById('year_id');
  const ministrySelect = document.getElementById('ministry_id');

  function loadMinistries(yearId, selectedMinistryId = null) {
    ministrySelect.innerHTML = '<option value="">-- Pilih Kementerian --</option>';
    if (!yearId) return;
    fetch(`/api/ministries/${yearId}`)
      .then(r => r.json())
      .then(ministries => {
        ministries.forEach(m => {
          const opt = document.createElement('option');
          opt.value = m.id;
          opt.textContent = m.name;
          if (selectedMinistryId && m.id == selectedMinistryId) opt.selected = true;
          ministrySelect.appendChild(opt);
        });
      });
  }

  yearSelect.addEventListener('change', function () {
    loadMinistries(this.value);
  });
})();
</script>
@endsection