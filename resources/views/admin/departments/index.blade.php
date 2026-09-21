@extends('layouts.admin')
@section('title','Departemen')
@section('content')
<div class="card">
  <div class="card-header">
    <span><i class="bi bi-diagram-3 me-2"></i>Departemen</span>
    <a href="{{ route('admin.departemen.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Departemen
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

    @if($departments->isEmpty())
      <p class="text-center text-muted py-4">Belum ada departemen untuk kabinet ini.</p>
    @else

    {{-- Group per kementerian --}}
    @php $grouped = $departments->groupBy(fn($d) => $d->ministry_id); @endphp

    @foreach($grouped as $ministryId => $depts)
    @php $ministry = $depts->first()->ministry; @endphp

    {{-- Header kementerian --}}
    <div class="dept-ministry-header">
      @if($ministry?->logo_path)
        <img src="{{ asset('storage/'.$ministry->logo_path) }}" class="dept-ministry-logo" alt="">
      @else
        <span class="dept-ministry-icon"><i class="bi bi-building"></i></span>
      @endif
      <div>
        <span class="dept-ministry-name">{{ $ministry?->name ?? 'Tanpa Kementerian' }}</span>
        <span class="dept-ministry-meta">{{ $depts->count() }} departemen</span>
      </div>
    </div>

    {{-- Tabel departemen --}}
    <div class="table-responsive mb-4">
      <table class="table table-hover mb-0 table-sm">
        <thead class="table-light">
          <tr>
            <th style="width:36px">#</th>
            <th>Nama Departemen</th>
            <th style="width:70px">Urutan</th>
            <th style="width:90px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($depts as $i => $d)
          <tr>
            <td class="text-muted small">{{ $i + 1 }}</td>
            <td><strong>{{ $d->name }}</strong></td>
            <td><span class="badge bg-light text-dark">{{ $d->sort_order }}</span></td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.departemen.edit', $d->id) }}"
                   class="btn btn-sm btn-outline-primary" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('admin.departemen.destroy', $d->id) }}"
                      onsubmit="return confirm('Hapus departemen {{ $d->name }}?')">
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
.dept-ministry-header {
  display: flex;
  align-items: center;
  gap: .7rem;
  padding: .55rem .85rem;
  background: linear-gradient(135deg, #f0eaff, #e8e0ff);
  border-left: 4px solid #4a1e8a;
  border-radius: 10px;
  margin-bottom: .5rem;
}
.dept-ministry-logo {
  width: 32px; height: 32px;
  object-fit: contain; flex-shrink: 0;
}
.dept-ministry-icon {
  width: 32px; height: 32px;
  background: #e9d8fd; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  color: #4a1e8a; font-size: 1rem; flex-shrink: 0;
}
.dept-ministry-name { display:block; font-weight:700; font-size:.86rem; color:#1a0a3e; }
.dept-ministry-meta { font-size:.7rem; color:#7c3aed; }
</style>
@endsection