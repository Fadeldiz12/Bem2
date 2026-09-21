@extends('layouts.admin')
@section('title', $year ? 'Edit Kabinet' : 'Tambah Kabinet')
@section('content')
<div class="card" style="max-width:860px">
  <div class="card-header">
    <i class="bi bi-archive me-2"></i>{{ $year ? 'Edit Kabinet' : 'Buat Kabinet Baru' }}
    <span class="badge ms-2 {{ $year?->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
      {{ $year?->status ?? 'draft (baru)' }}
    </span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ $year ? route('admin.kabinet.update', $year->id) : route('admin.kabinet.store') }}" enctype="multipart/form-data">
      @csrf

      {{-- INFO KABINET --}}
      <h6 class="fw-bold mb-3" style="color:#6d28d9"><i class="bi bi-info-circle me-1"></i>Informasi Kabinet</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6"><label class="form-label fw-semibold">Tahun *</label><input type="text" name="year_label" class="form-control" value="{{ old('year_label', $year?->year_label) }}" placeholder="2025/2026" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Nama Kabinet *</label><input type="text" name="cabinet_name" class="form-control" value="{{ old('cabinet_name', $year?->cabinet_name) }}" required></div>
        <div class="col-12"><label class="form-label fw-semibold">Tagline</label><input type="text" name="tagline" class="form-control" value="{{ old('tagline', $year?->tagline) }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal Mulai</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date', $year?->start_date?->format('Y-m-d')) }}"></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Tanggal Selesai</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date', $year?->end_date?->format('Y-m-d')) }}"></div>
        <div class="col-12">
          <label class="form-label fw-semibold">Logo Kabinet</label>
          <input type="file" name="logo_path" class="form-control" accept="image/*">
          @if($year?->logo_path)<img src="{{ asset('storage/'.$year->logo_path) }}" class="mt-2 rounded" style="height:60px">@endif
        </div>
        <div class="col-12"><label class="form-label fw-semibold">Visi</label><textarea name="visi" class="form-control" rows="3">{{ old('visi', $year?->visi) }}</textarea></div>
        <div class="col-12">
          <label class="form-label fw-semibold">Misi <small class="text-muted">(satu baris satu poin)</small></label>
          <textarea name="misi" class="form-control" rows="5">{{ old('misi', $misi_text) }}</textarea>
        </div>
      </div>

      {{-- PRESMA & WAPRESMA --}}
      <h6 class="fw-bold mb-3" style="color:#6d28d9"><i class="bi bi-person-fill me-1"></i>Presiden & Wakil Presiden Mahasiswa</h6>
      <div class="row g-3 mb-4">
        <div class="col-md-6"><label class="form-label fw-semibold">Nama Presma</label><input type="text" name="presma_name" class="form-control" value="{{ old('presma_name', $year?->presma_name) }}"></div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Foto Presma</label>
          <input type="file" name="presma_photo" class="form-control" accept="image/*">
          @if($year?->presma_photo)<img src="{{ asset('storage/'.$year->presma_photo) }}" class="mt-2 rounded" style="height:60px">@endif
        </div>
        <div class="col-md-6"><label class="form-label fw-semibold">Nama Wapresma</label><input type="text" name="wapresma_name" class="form-control" value="{{ old('wapresma_name', $year?->wapresma_name) }}"></div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Foto Wapresma</label>
          <input type="file" name="wapresma_photo" class="form-control" accept="image/*">
          @if($year?->wapresma_photo)<img src="{{ asset('storage/'.$year->wapresma_photo) }}" class="mt-2 rounded" style="height:60px">@endif
        </div>
      </div>

      {{-- FILOSOFI LOGO --}}
      <h6 class="fw-bold mb-1" style="color:#6d28d9"><i class="bi bi-vector-pen me-1"></i>Filosofi Logo</h6>
      <p class="text-muted small mb-3">Setiap elemen bisa diberi gambar ikon tersendiri. Tampil di halaman Profil.</p>
      <div id="filosofiContainer">
        @php $filosofiList = $year?->filosofi_list ?? []; @endphp
        @forelse($filosofiList as $i => $f)
        <div class="filosofi-row border rounded-3 p-3 mb-2 bg-light">
          <div class="row g-2">
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Nama Elemen</label>
              <input type="text" name="filosofi[{{ $i }}][nama]" class="form-control form-control-sm" value="{{ $f['nama'] ?? '' }}" placeholder="Dua Naga...">
            </div>
            <div class="col-md-5">
              <label class="form-label small fw-semibold">Penjelasan</label>
              <textarea name="filosofi[{{ $i }}][penjelasan]" class="form-control form-control-sm" rows="2">{{ $f['penjelasan'] ?? '' }}</textarea>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Gambar Elemen <small class="text-muted">(ikon)</small></label>
              <input type="file" name="filosofi[{{ $i }}][gambar]" class="form-control form-control-sm" accept="image/*">
              @if(!empty($f['gambar']))<img src="{{ asset('storage/'.$f['gambar']) }}" class="mt-1 rounded" style="height:36px">@endif
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button type="button" class="btn btn-sm btn-outline-danger remove-filosofi w-100">×</button>
            </div>
          </div>
        </div>
        @empty
        <p class="text-muted small fst-italic" id="filosofiEmpty">Belum ada filosofi.</p>
        @endforelse
      </div>
      <button type="button" id="addFilosofi" class="btn btn-sm btn-outline-primary mb-4">
        <i class="bi bi-plus-lg me-1"></i>Tambah Elemen Filosofi
      </button>

      {{-- MAKNA WARNA --}}
      <h6 class="fw-bold mb-1" style="color:#6d28d9"><i class="bi bi-palette me-1"></i>Makna Warna</h6>
      <p class="text-muted small mb-3">Tampil di halaman Profil.</p>
      <div id="warnaContainer">
        @php $warnaList = $year?->warna_list ?? []; @endphp
        @forelse($warnaList as $i => $w)
        <div class="warna-row border rounded-3 p-3 mb-2 bg-light">
          <div class="row g-2 align-items-center">
            <div class="col-md-3">
              <label class="form-label small fw-semibold">Nama Warna</label>
              <input type="text" name="warna[{{ $i }}][nama]" class="form-control form-control-sm" value="{{ $w['nama'] ?? '' }}" placeholder="Vivid Purple">
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-semibold">Kode Hex</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text swatch-box" style="background:{{ $w['hex'] ?? '#fff' }};width:30px;border-right:none;padding:0"></span>
                <input type="text" name="warna[{{ $i }}][hex]" class="form-control form-control-sm hex-input" value="{{ $w['hex'] ?? '' }}" placeholder="#4a1e8a" maxlength="7">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Makna / Deskripsi</label>
              <input type="text" name="warna[{{ $i }}][makna]" class="form-control form-control-sm" value="{{ $w['makna'] ?? '' }}" placeholder="Membangun keberanian...">
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button type="button" class="btn btn-sm btn-outline-danger remove-warna w-100">×</button>
            </div>
          </div>
        </div>
        @empty
        <p class="text-muted small fst-italic" id="warnaEmpty">Belum ada makna warna.</p>
        @endforelse
      </div>
      <button type="button" id="addWarna" class="btn btn-sm btn-outline-primary mb-4">
        <i class="bi bi-plus-lg me-1"></i>Tambah Makna Warna
      </button>

      <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
        @if($year)<a href="{{ route('admin.kabinet.preview', $year->id) }}" target="_blank" class="btn btn-outline-info"><i class="bi bi-eye me-1"></i>Preview</a>@endif
        <a href="{{ route('admin.kabinet.index') }}" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection
@section('scripts')
<script>
let filosofiIdx = {{ count($year?->filosofi_list ?? []) }};
let warnaIdx    = {{ count($year?->warna_list ?? []) }};

document.getElementById('addFilosofi').addEventListener('click', function () {
  document.getElementById('filosofiEmpty')?.remove();
  const div = document.createElement('div');
  div.className = 'filosofi-row border rounded-3 p-3 mb-2 bg-light';
  div.innerHTML = `
    <div class="row g-2">
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Nama Elemen</label>
        <input type="text" name="filosofi[${filosofiIdx}][nama]" class="form-control form-control-sm" placeholder="Dua Naga...">
      </div>
      <div class="col-md-5">
        <label class="form-label small fw-semibold">Penjelasan</label>
        <textarea name="filosofi[${filosofiIdx}][penjelasan]" class="form-control form-control-sm" rows="2"></textarea>
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Gambar Elemen <small class="text-muted">(ikon)</small></label>
        <input type="file" name="filosofi[${filosofiIdx}][gambar]" class="form-control form-control-sm" accept="image/*">
      </div>
      <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-sm btn-outline-danger remove-filosofi w-100">×</button>
      </div>
    </div>`;
  document.getElementById('filosofiContainer').appendChild(div);
  filosofiIdx++;
});

document.getElementById('addWarna').addEventListener('click', function () {
  document.getElementById('warnaEmpty')?.remove();
  const div = document.createElement('div');
  div.className = 'warna-row border rounded-3 p-3 mb-2 bg-light';
  div.innerHTML = `
    <div class="row g-2 align-items-center">
      <div class="col-md-3">
        <label class="form-label small fw-semibold">Nama Warna</label>
        <input type="text" name="warna[${warnaIdx}][nama]" class="form-control form-control-sm" placeholder="Vivid Purple">
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold">Kode Hex</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text swatch-box" style="background:#fff;width:30px;border-right:none;padding:0"></span>
          <input type="text" name="warna[${warnaIdx}][hex]" class="form-control form-control-sm hex-input" placeholder="#4a1e8a" maxlength="7">
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-semibold">Makna / Deskripsi</label>
        <input type="text" name="warna[${warnaIdx}][makna]" class="form-control form-control-sm" placeholder="Membangun keberanian...">
      </div>
      <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-sm btn-outline-danger remove-warna w-100">×</button>
      </div>
    </div>`;
  document.getElementById('warnaContainer').appendChild(div);
  warnaIdx++;
});

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-filosofi')) e.target.closest('.filosofi-row').remove();
  if (e.target.classList.contains('remove-warna'))   e.target.closest('.warna-row').remove();
});

document.addEventListener('input', function(e) {
  if (!e.target.classList.contains('hex-input')) return;
  const val    = e.target.value;
  const swatch = e.target.closest('.input-group')?.querySelector('.swatch-box');
  if (/^#[0-9A-Fa-f]{6}$/.test(val) && swatch) swatch.style.background = val;
});
</script>
@endsection