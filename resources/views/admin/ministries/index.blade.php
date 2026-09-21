@extends('layouts.admin')
@section('title','Kementerian')
@section('content')

<div class="card">
  <div class="card-header">
    <span><i class="bi bi-building me-2"></i>Kementerian</span>
    <a href="{{ route('admin.kementerian.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>Tambah Kementerian
    </a>
  </div>
  <div class="card-body pb-0">
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
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th class="d-none d-sm-table-cell">Logo</th>
          <th>Nama</th>
          <th class="d-none d-md-table-cell">Alias</th>
          <th class="d-none d-md-table-cell">WhatsApp</th>
          <th class="d-none d-lg-table-cell">Urutan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($ministries as $i => $m)
        <tr>
          <td>{{ $i+1 }}</td>
          <td class="d-none d-sm-table-cell">
            @if($m->logo_path)
              <img src="{{ asset('storage/'.$m->logo_path) }}" style="height:36px">
            @else
              <i class="bi bi-image text-muted"></i>
            @endif
          </td>
          <td><strong>{{ $m->name }}</strong></td>
          <td class="d-none d-md-table-cell">
            <span class="badge bg-light text-dark">{{ $m->alias }}</span>
          </td>
          <td class="d-none d-md-table-cell">
            <small>{{ $m->whatsapp_number ?? '-' }}</small>
          </td>
          <td class="d-none d-lg-table-cell">{{ $m->sort_order }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.kementerian.edit', $m->id) }}"
                 class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.kementerian.destroy', $m->id) }}"
                    onsubmit="return confirm('Hapus kementerian ini?')">
                @csrf
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">
            Belum ada kementerian untuk tahun ini.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection