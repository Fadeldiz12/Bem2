@extends('layouts.admin')
@section('title','Pengurus')
@section('content')
<div class="card">
  <div class="card-header">
    <span><i class="bi bi-people me-2"></i>Pengurus</span>
    <a href="{{ route('admin.pengurus.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Pengurus
    </a>
  </div>
  <div class="card-body">

    {{-- Filter tahun --}}
    <form method="GET" class="mb-3" style="max-width:280px">
      <select name="year_id" class="form-select" onchange="this.form.submit()">
        @foreach($years as $y)
          <option value="{{ $y->id }}" {{ $selected_year == $y->id ? 'selected' : '' }}>
            {{ $y->year_label }}
          </option>
        @endforeach
      </select>
    </form>

    @if($members->isEmpty())
      <p class="text-center text-muted py-4">Belum ada pengurus.</p>
    @else

    {{-- Group per kementerian --}}
    @php $byMinistry = $members->groupBy(fn($m) => $m->ministry_id ?? 0); @endphp

    @foreach($byMinistry as $ministryId => $ministryMembers)
    @php $ministry = $ministryMembers->first()->ministry; @endphp

    {{-- Header kementerian --}}
    <div class="group-header">
      @if($ministry?->logo_path)
        <img src="{{ asset('storage/'.$ministry->logo_path) }}" class="group-logo" alt="">
      @else
        <span class="group-icon"><i class="bi bi-building"></i></span>
      @endif
      <div>
        <span class="group-name">{{ $ministry?->name ?? 'Tanpa Kementerian' }}</span>
      </div>
    </div>

    {{-- Tabel semua anggota kementerian ini (menteri + semua dept dalam 1 tabel) --}}
    <div class="table-responsive mb-4">
      <table class="table table-hover mb-0 table-sm">
        <thead class="table-light">
          <tr>
            <th style="width:36px">#</th>
            <th style="width:44px">Foto</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Departemen</th>
            <th style="width:80px">Status</th>
            <th style="width:90px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($ministryMembers as $i => $m)
          <tr class="{{ $m->role === 'menteri' ? 'tr-menteri' : ($m->is_head ? 'tr-kadep' : '') }}">
            <td class="text-muted small">{{ $i + 1 }}</td>
            <td>
              @if($m->photo_path)
                <img src="{{ asset('storage/'.$m->photo_path) }}"
                     style="width:34px;height:34px;border-radius:50%;object-fit:cover;object-position:top"
                     loading="lazy">
              @else
                <i class="bi bi-person-circle text-muted fs-4"></i>
              @endif
            </td>
            <td>
              <strong>{{ $m->full_name }}</strong>
              @if($m->role === 'menteri')
                <span class="badge ms-1" style="background:#4a1e8a;font-size:.62rem">Menteri</span>
              @endif
            </td>
            <td><span class="badge bg-light text-dark">{{ $m->position }}</span></td>
            <td>
              @if($m->department)
                <small class="text-muted">{{ $m->department->name }}</small>
              @else
                <small class="text-muted">-</small>
              @endif
            </td>
            <td>
              @if($m->role === 'menteri')
                <span class="text-muted small">-</span>
              @elseif($m->is_head)
                <span class="badge bg-success">Kepala</span>
              @else
                <span class="text-muted small">Staf</span>
              @endif
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.pengurus.edit', $m->id) }}"
                   class="btn btn-sm btn-outline-primary" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('admin.pengurus.destroy', $m->id) }}"
                      onsubmit="return confirm('Hapus {{ $m->full_name }}?')">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger" title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if(!$loop->last)
      <hr style="border-color:rgba(74,30,138,.1);margin-bottom:1.5rem">
    @endif

    @endforeach
    @endif

  </div>
</div>
@endsection

@section('scripts')
<style>
.group-header {
  display: flex;
  align-items: center;
  gap: .7rem;
  padding: .55rem .85rem;
  background: linear-gradient(135deg, #f0eaff, #e8e0ff);
  border-left: 4px solid #4a1e8a;
  border-radius: 10px;
  margin-bottom: .5rem;
}
.group-logo { width:32px;height:32px;object-fit:contain;flex-shrink:0; }
.group-icon {
  width:32px;height:32px;border-radius:8px;
  background:#e9d8fd;
  display:flex;align-items:center;justify-content:center;
  color:#4a1e8a;font-size:1rem;flex-shrink:0;
}
.group-name { display:block;font-weight:700;font-size:.86rem;color:#1a0a3e; }
.group-meta { font-size:.7rem;color:#7c3aed; }

.tr-menteri td { background:rgba(74,30,138,.04); }
.tr-kadep   td { background:rgba(124,58,237,.03); }
</style>
@endsection