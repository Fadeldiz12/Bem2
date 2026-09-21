@extends('layouts.public')
@section('title','Berita & Pengumuman')
@section('content')
<section class="container py-5">
  <div class="text-center mb-5"><span class="page-pill">Berita & Pengumuman</span></div>
  <div class="d-flex gap-2 justify-content-center mb-4 flex-wrap">
    <a href="{{ route('berita') }}" class="btn btn-sm {{ !$category ? 'btn-purple' : 'btn-outline-secondary' }} rounded-pill">Semua</a>
    @foreach(['berita','pengumuman','kegiatan','artikel'] as $c)
      <a href="{{ route('berita') }}?kategori={{ $c }}" class="btn btn-sm {{ $category===$c ? 'btn-purple' : 'btn-outline-secondary' }} rounded-pill">{{ ucfirst($c) }}</a>
    @endforeach
  </div>
  <div class="row g-4">
    @forelse($posts as $p)
    <div class="col-md-4">
      <div class="card-soft h-100 overflow-hidden">
        @if($p->featured_image)<img src="{{ asset('storage/'.$p->featured_image) }}" class="w-100" style="height:180px;object-fit:cover">@endif
        <div class="p-3">
          <span class="badge mb-2" style="background:var(--purple-pale);color:var(--purple-deep)">{{ $p->category }}</span>
          <h6 class="fw-bold">{{ $p->title }}</h6>
          <small class="text-muted d-block mb-2">{{ $p->published_at?->format('d M Y') }}</small>
          <a href="{{ route('berita.detail', $p->slug) }}" class="small fw-semibold" style="color:var(--purple)">Baca selengkapnya →</a>
        </div>
      </div>
    </div>
    @empty<p class="text-center text-muted">Belum ada berita.</p>
    @endforelse
  </div>
  <div class="mt-4 d-flex justify-content-center">{{ $posts->links() }}</div>
</section>
@endsection
