@extends('layouts.admin')
@section('title', $member ? 'Edit Pengurus' : 'Tambah Pengurus')
@section('content')
<div class="card" style="max-width:700px">
  <div class="card-header"><i class="bi bi-people me-2"></i>{{ $member ? 'Edit Pengurus' : 'Tambah Pengurus' }}</div>
  <div class="card-body">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form method="POST" action="{{ $member ? route('admin.pengurus.update', $member->id) : route('admin.pengurus.store') }}" enctype="multipart/form-data" id="memberForm" novalidate>
      @csrf

      {{-- ── PENEMPATAN ── --}}
      <p class="text-uppercase fw-bold small text-muted mb-2 mt-1" style="letter-spacing:.08em">Penempatan</p>
      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tahun Kepengurusan *</label>
          <select name="management_year_id" id="management_year_id" class="form-select @error('management_year_id') is-invalid @enderror" required>
            @foreach($years as $y)
              <option value="{{ $y->id }}" {{ old('management_year_id', $member?->management_year_id) == $y->id ? 'selected' : '' }}>
                {{ $y->year_label }}
              </option>
            @endforeach
          </select>
          @error('management_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Kementerian *</label>
          <select name="ministry_id" id="ministry_id" class="form-select @error('ministry_id') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach($ministries as $m)
              <option value="{{ $m->id }}" {{ old('ministry_id', $member?->ministry_id) == $m->id ? 'selected' : '' }}>
                {{ $m->name }}
              </option>
            @endforeach
          </select>
          @error('ministry_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Jabatan (Role) — dropdown sistem --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Jabatan *</label>
          <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach(['menteri'=>'Menteri','kepala_departemen'=>'Kepala Departemen','staff'=>'Staff'] as $k=>$v)
              <option value="{{ $k }}" {{ old('role', $member?->role ?? 'staff') === $k ? 'selected' : '' }}>
                {{ $v }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Menentukan posisi dalam sistem.</div>
          @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Departemen — muncul/hilang sesuai role --}}
        <div class="col-md-6" id="dept_wrap">
          <label class="form-label fw-semibold">Departemen</label>
          <select name="department_id" id="department_id" class="form-select">
            <option value="">-- Tidak Ada --</option>
            @foreach($departments as $d)
              <option value="{{ $d->id }}" {{ old('department_id', $member?->department_id) == $d->id ? 'selected' : '' }}>
                {{ $d->name }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Kosongkan jika Menteri.</div>
        </div>
      </div>

      <hr class="my-3">

      {{-- ── DATA ANGGOTA ── --}}
      <p class="text-uppercase fw-bold small text-muted mb-2" style="letter-spacing:.08em">Data Anggota</p>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Lengkap *</label>
          <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                 value="{{ old('full_name', $member?->full_name) }}" required placeholder="Nama lengkap pengurus">
          @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">NIM</label>
          <input type="text" name="nim" class="form-control"
                 value="{{ old('nim', $member?->nim) }}" placeholder="Nomor Induk Mahasiswa">
        </div>

        {{-- Position — label tampilan, auto-fill dari role tapi bisa diubah --}}
        <div class="col-md-6">
          <label class="form-label fw-semibold">Jabatan Tampilan *
            <span class="text-muted fw-normal small">(teks yang muncul di website)</span>
          </label>
          <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror"
                 value="{{ old('position', $member?->position) }}"
                 placeholder="Contoh: Menteri, Kepala Departemen, Staf Ahli"
                 required>
          <div class="form-text">Otomatis terisi saat pilih Jabatan, bisa diubah manual.</div>
          @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Urutan Tampilan</label>
          <input type="number" name="sort_order" class="form-control" min="0"
                 value="{{ old('sort_order', $member?->sort_order ?? 1) }}">
          <div class="form-text">Angka kecil tampil lebih awal.</div>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Foto</label>
          <input type="file" name="photo_path" id="photo_path" class="form-control @error('photo_path') is-invalid @enderror" accept="image/*">
          @if($member?->photo_path)
            <img src="{{ asset('storage/'.$member->photo_path) }}"
                 class="mt-2 rounded" style="height:80px;object-fit:cover" loading="lazy">
            <div class="form-text">Biarkan kosong jika tidak ingin mengganti. Maks. 2MB.</div>
          @else
            <div class="form-text">Maks. 2MB.</div>
          @endif
          @error('photo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" id="submitBtn" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        <a href="{{ route('admin.pengurus.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
  const form         = document.getElementById('memberForm');
  const submitBtn    = document.getElementById('submitBtn');
  const yearSelect   = document.getElementById('management_year_id');
  const roleSelect   = document.getElementById('role');
  const deptWrap     = document.getElementById('dept_wrap');
  const posInput     = document.getElementById('position');
  const ministrySelect = document.getElementById('ministry_id');
  const deptSelect   = document.getElementById('department_id');

  // Map role → label default untuk position
  const roleLabels = {
    menteri           : 'Menteri',
    kepala_departemen : 'Kepala Departemen',
    staff             : 'Staf',
  };

  // ── Validasi & toggle tombol Simpan ──
  const requiredFields = [ministrySelect, roleSelect, posInput,
    document.querySelector('[name="full_name"]')];

  function checkFormValidity() {
    const allFilled = requiredFields.every(el => el.value.trim() !== '');
    submitBtn.disabled = !allFilled;
    submitBtn.classList.toggle('opacity-50', !allFilled);
  }

  requiredFields.forEach(el => {
    el.addEventListener('input', checkFormValidity);
    el.addEventListener('change', checkFormValidity);
  });

  // ── Submit: validasi manual + highlight merah ──
  form.addEventListener('submit', function (e) {
    let valid = true;
    requiredFields.forEach(el => {
      if (el.value.trim() === '') {
        el.classList.add('is-invalid');
        valid = false;
      } else {
        el.classList.remove('is-invalid');
      }
    });
    if (!valid) {
      e.preventDefault();
      const firstInvalid = form.querySelector('.is-invalid');
      if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });

  // Hapus highlight merah saat user mulai mengisi
  requiredFields.forEach(el => {
    el.addEventListener('input', () => el.classList.remove('is-invalid'));
    el.addEventListener('change', () => el.classList.remove('is-invalid'));
  });

  // ── Sembunyikan departemen jika role = menteri ──
  function toggleDeptVisibility() {
    const isMenteri = roleSelect.value === 'menteri';
    deptWrap.style.display = isMenteri ? 'none' : '';
    if (isMenteri) deptSelect.value = '';
  }

  // ── Auto-fill position dari role ──
  const defaultLabels = Object.values(roleLabels);
  function autoFillPosition() {
    const current = posInput.value.trim();
    if (current === '' || defaultLabels.includes(current)) {
      posInput.value = roleLabels[roleSelect.value] ?? '';
    }
  }

  roleSelect.addEventListener('change', function () {
    toggleDeptVisibility();
    autoFillPosition();
    checkFormValidity();
  });

  // ── Load kementerian via AJAX saat tahun berubah ──
  function loadMinistries(yearId, selectedMinistryId = null) {
    ministrySelect.innerHTML = '<option value="">-- Pilih --</option>';
    deptSelect.innerHTML     = '<option value="">-- Tidak Ada --</option>';
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
        if (selectedMinistryId) loadDepartments(selectedMinistryId, {{ $member?->department_id ?? 'null' }});
        checkFormValidity();
      });
  }

  yearSelect.addEventListener('change', function () {
    loadMinistries(this.value);
  });

  // ── Load departemen via AJAX saat kementerian berubah ──
  function loadDepartments(ministryId, selectedDeptId = null) {
    deptSelect.innerHTML = '<option value="">-- Tidak Ada --</option>';
    if (!ministryId) return;
    fetch(`/api/departments/${ministryId}`)
      .then(r => r.json())
      .then(depts => {
        depts.forEach(d => {
          const opt = document.createElement('option');
          opt.value = d.id;
          opt.textContent = d.name;
          if (selectedDeptId && d.id == selectedDeptId) opt.selected = true;
          deptSelect.appendChild(opt);
        });
      });
  }

  ministrySelect.addEventListener('change', function () {
    loadDepartments(this.value);
    checkFormValidity();
  });

  // ── Init saat halaman load ──
  toggleDeptVisibility();
  if (posInput.value.trim() === '') {
    posInput.value = roleLabels[roleSelect.value] ?? '';
  }
  checkFormValidity();
})();
</script>
@endsection