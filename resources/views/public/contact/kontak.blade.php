@extends('layouts.public')
@section('title','Kontak')
@section('content')
<section class="container py-5">
  <div class="text-center mb-5"><span class="page-pill">Kontak</span></div>
  @forelse($contacts as $c)
  <div class="mb-4">
    <h6 class="fw-bold mb-2" style="color:var(--purple-deep)">{{ $c->whatsapp_label ?: $c->name }}</h6>
    <a href="https://wa.me/{{ $c->whatsapp_number }}" target="_blank" class="btn btn-purple d-inline-flex align-items-center gap-2">
      <i class="bi bi-whatsapp"></i> {{ $c->whatsapp_label ?: $c->name }}
    </a>
  </div>
  @empty<p class="text-center text-muted">Belum ada kontak tersedia.</p>
  @endforelse
</section>
@endsection
