@extends('layouts.admin')
@section('title', $post ? 'Edit Post' : 'Tambah Post')
@section('content')
<div class="card" style="max-width:800px">
  <div class="card-header"><i class="bi bi-newspaper me-2"></i>{{ $post ? 'Edit Post' : 'Tambah Post' }}</div>
  <div class="card-body">
    <form method="POST" action="{{ $post ? route('admin.berita.update', $post->id) : route('admin.berita.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-12"><label class="form-label fw-semibold">Judul *</label><input type="text" name="title" class="form-control" value="{{ old('title', $post?->title) }}" required></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Kategori</label><select name="category" class="form-select">@foreach(['berita','pengumuman','kegiatan','artikel'] as $c)<option value="{{ $c }}" {{ old('category', $post?->category ?? 'berita')===$c?'selected':'' }}>{{ ucfirst($c) }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select">@foreach(['draft','published','archived'] as $s)<option value="{{ $s }}" {{ old('status', $post?->status ?? 'draft')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div class="col-12"><label class="form-label fw-semibold">Gambar Utama</label><input type="file" name="featured_image" class="form-control" accept="image/*">@if($post?->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" class="mt-2 rounded" style="height:100px">@endif</div>
        <div class="col-md-4"><label class="form-label fw-semibold">Gambar Konten 1</label><input type="file" name="content_image_1" class="form-control" accept="image/*">@if($post?->content_image_1)<img src="{{ asset('storage/'.$post->content_image_1) }}" class="mt-2 rounded" style="height:100px">@endif</div>
        <div class="col-md-4"><label class="form-label fw-semibold">Gambar Konten 2</label><input type="file" name="content_image_2" class="form-control" accept="image/*">@if($post?->content_image_2)<img src="{{ asset('storage/'.$post->content_image_2) }}" class="mt-2 rounded" style="height:100px">@endif</div>
        <div class="col-md-4"><label class="form-label fw-semibold">Gambar Konten 3</label><input type="file" name="content_image_3" class="form-control" accept="image/*">@if($post?->content_image_3)<img src="{{ asset('storage/'.$post->content_image_3) }}" class="mt-2 rounded" style="height:100px">@endif</div>
        <div class="col-12"><label class="form-label fw-semibold">Konten *</label><textarea name="content" class="form-control" rows="10" required>{{ old('content', $post?->content) }}</textarea></div>
      </div>
      <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button><a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </form>
  </div>
</div>
@endsection
