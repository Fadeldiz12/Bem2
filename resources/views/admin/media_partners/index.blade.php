@extends('layouts.admin')
@section('title','Media Partner')
@section('content')
<div class="row g-3">
  @foreach($partners as $p)
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-handshake me-2"></i>{{ ucfirst($p->type) }}</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.media.update', $p->id) }}">
          @csrf
          <div class="mb-3"><label class="form-label fw-semibold">Judul</label><input type="text" name="title" class="form-control" value="{{ $p->title }}"></div>
          <div class="mb-3"><label class="form-label fw-semibold">Prosedur <small class="text-muted">(satu baris satu langkah)</small></label><textarea name="procedures" class="form-control" rows="6">{{ implode("\n", $p->procedures_list) }}</textarea></div>
          <div class="mb-3"><label class="form-label fw-semibold">Link Google Form</label><input type="text" name="gform_link" class="form-control" value="{{ $p->gform_link }}"></div>
          <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Simpan</button>
        </form>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
