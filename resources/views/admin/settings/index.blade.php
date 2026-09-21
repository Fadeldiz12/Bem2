@extends('layouts.admin')
@section('title','Pengaturan Situs')
@section('content')
<div class="card" style="max-width:760px">
  <div class="card-header"><i class="bi bi-gear me-2"></i>Pengaturan Situs</div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.setting.update') }}">
      @csrf
      <div class="row g-3">
        @foreach($setting_items as $s)
        <div class="col-12">
          <label class="form-label fw-semibold">{{ $s->label }}</label>
          @if($s->type === 'textarea')
            <textarea name="{{ $s->key }}" class="form-control" rows="3">{{ $s->value }}</textarea>
          @else
            <input type="text" name="{{ $s->key }}" class="form-control" value="{{ $s->value }}">
          @endif
        </div>
        @endforeach
      </div>
      <button type="submit" class="btn btn-primary mt-4"><i class="bi bi-save me-1"></i>Simpan Pengaturan</button>
    </form>
  </div>
</div>
@endsection
