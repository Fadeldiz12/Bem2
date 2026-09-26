@extends('layouts.public')
@section('title','Arsip Kabinet')
@section('meta_description','Arsip kabinet BEM Politeknik Negeri Medan (BEM Polmed) dari periode ke periode: nama kabinet, logo, presiden mahasiswa, dan jajaran pengurus.')
@section('content')
<section class="container py-5">
  <div class="text-center mb-5"><h1 class="page-pill">Arsip Kabinet</h1></div>
  <div class="row g-4">
    @forelse($archived_years as $y)
    <div class="col-md-4">
      <a href="{{ route('arsip.detail', $y->id) }}" class="text-decoration-none">
        <div class="card-soft p-4 text-center h-100">
          @if($y->logo_path)<img src="{{ asset('storage/'.$y->logo_path) }}" style="height:90px" class="mb-3" alt="Logo {{ $y->cabinet_name }}">@else<i class="bi bi-archive fs-1 mb-3" style="color:var(--purple)"></i>@endif
          <h6 class="fw-bold mb-1" style="color:var(--purple-deep)">{{ $y->cabinet_name }}</h6>
          <small class="text-muted d-block mb-2">{{ $y->year_label }}</small>
          <span class="badge" style="background:var(--purple-pale);color:var(--purple-deep)">Arsip</span>
        </div>
      </a>
    </div>
    @empty<p class="text-center text-muted">Belum ada kabinet yang diarsipkan.</p>
    @endforelse
  </div>
</section>
@endsection
