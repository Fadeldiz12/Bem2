@extends('layouts.public')
@section('title','Media Partner')
@section('content')
<section class="container py-5">
  <div class="text-center mb-5"><span class="page-pill">Media Partner</span></div>
  @foreach($partners as $p)
  <div class="mb-5">
    <h4 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:var(--purple-deep)">
      <i class="bi bi-{{ $p->type === 'internal' ? 'box-arrow-in-down' : 'box-arrow-up-right' }}"></i> {{ $p->title }}
    </h4>
    <ol class="mb-3">@foreach($p->procedures_list as $proc)<li class="mb-2" style="color:var(--purple)">{{ $proc }}</li>@endforeach</ol>
    @if($p->gform_link && $p->gform_link !== '#')
      @php
        $link = Str::startsWith($p->gform_link, ['http://', 'https://']) ? $p->gform_link : 'https://' . $p->gform_link;
      @endphp
      <a href="{{ $link }}" target="_blank" class="btn btn-light rounded-pill px-4 fw-semibold" style="color:var(--purple-deep)">Prosedur</a>
    @endif
  </div>
  @endforeach
</section>
@endsection
