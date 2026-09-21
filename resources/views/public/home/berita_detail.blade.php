@extends('layouts.public')
@section('title', $post->title)
@section('content')
<section class="container py-5" style="max-width:760px">
  <span class="badge mb-3" style="background:var(--purple-pale);color:var(--purple-deep)">{{ $post->category }}</span>
  <h2 class="font-serif fw-bold mb-2" style="color:var(--purple-deep)">{{ $post->title }}</h2>
  <p class="text-muted small mb-4">Oleh {{ $post->author?->name }} · {{ $post->published_at?->format('d M Y') }}</p>
  @if($post->featured_image)<img src="{{ asset('storage/'.$post->featured_image) }}" class="w-100 rounded-4 mb-4" style="max-height:400px;object-fit:cover">@endif
  <div>{!! nl2br(e($post->content)) !!}</div>
  @php
    $contentImages = array_filter([
      $post->content_image_1,
      $post->content_image_2,
      $post->content_image_3,
    ]);
  @endphp
  @if(count($contentImages))
    <div class="row g-3 mt-4">
      @foreach($contentImages as $image)
        <div class="col-12 col-md-4">
          <img src="{{ asset('storage/'.$image) }}" class="w-100 rounded-4" style="object-fit:cover; min-height:220px; max-height:320px;">
        </div>
      @endforeach
    </div>
  @endif
  <a href="{{ route('berita') }}" class="btn btn-outline-secondary rounded-pill mt-4">← Kembali ke Berita</a>
</section>
@endsection
