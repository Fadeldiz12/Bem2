@extends('layouts.public')
@section('title','Format Surat')
@section('meta_description','Unduh format dan template surat resmi untuk HMPS dan UKM Politeknik Negeri Medan yang disediakan oleh BEM Polmed.')
@section('content')
<section class="container py-5">
  <div class="text-center mb-5"><h1 class="page-pill">Format Surat</h1></div>
  <div class="row g-4 justify-content-center">
    @forelse($formats as $f)
    <div class="col-6 col-md-3">
      <div class="surat-card">
        <div class="surat-icon-wrap">
          <i class="bi bi-{{ $f->icon_type ?? 'file-text' }} surat-icon"></i>
        </div>
        <span class="surat-title">{{ $f->title }}</span>
        <div class="d-flex flex-column gap-2 w-100 mt-3">
          @if($f->file_path_hmps)
            <a href="{{ route('download-surat', ['id' => $f->id, 'type' => 'hmps']) }}" class="btn btn-sm btn-white w-100">Download HMPS</a>
          @endif
          @if($f->file_path_ukm)
            <a href="{{ route('download-surat', ['id' => $f->id, 'type' => 'ukm']) }}" class="btn btn-sm btn-white w-100">Download UKM</a>
          @endif
          @if(!$f->file_path_hmps && !$f->file_path_ukm && $f->file_path)
            <a href="{{ route('download-surat', ['id' => $f->id]) }}" class="btn btn-sm btn-white w-100">Download</a>
          @endif
        </div>
      </div>
    </div>
    @empty
    <p class="text-center text-muted">Belum ada format surat tersedia.</p>
    @endforelse
  </div>
</section>
@endsection
@section('scripts')
<style>
.surat-card {
  background: var(--purple-pale);
  border-radius: 20px;
  padding: 2rem 1rem 1.4rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: .7rem;
  border: 2px solid transparent;
  transition: background .3s, border-color .3s, transform .3s, box-shadow .3s;
  cursor: pointer;
}
.surat-card:hover {
  background: var(--purple);
  border-color: var(--purple);
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(74,30,138,.25);
}

.surat-icon-wrap {
  width: 68px; height: 68px;
  border-radius: 50%;
  background: #fff;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 12px rgba(74,30,138,.12);
  transition: background .3s, box-shadow .3s;
}
.surat-card:hover .surat-icon-wrap {
  background: rgba(255,255,255,.2);
  box-shadow: none;
}

.surat-icon {
  font-size: 1.8rem;
  color: var(--purple);
  transition: color .3s;
}
.surat-card:hover .surat-icon { color: #fff; }

.surat-title {
  font-size: .85rem;
  font-weight: 700;
  color: var(--purple-deep);
  text-align: center;
  line-height: 1.3;
  transition: color .3s;
}
.surat-card:hover .surat-title { color: #fff; }

.surat-hint {
  font-size: .72rem;
  color: var(--purple);
  opacity: 0;
  transition: opacity .3s, color .3s;
}
.surat-card:hover .surat-hint { opacity: 1; color: rgba(255,255,255,.8); }
</style>
@endsection