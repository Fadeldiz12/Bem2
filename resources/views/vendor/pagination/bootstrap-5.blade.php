@if ($paginator->hasPages())
<nav>
  <ul class="pagination justify-content-center">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <li class="page-item disabled"><span class="page-link" style="border-radius:50px;margin:0 2px">‹</span></li>
    @else
      <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" style="border-radius:50px;margin:0 2px">‹</a></li>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <li class="page-item disabled"><span class="page-link" style="border-radius:50px;margin:0 2px">{{ $element }}</span></li>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <li class="page-item active"><span class="page-link" style="border-radius:50px;margin:0 2px;background:#6d28d9;border-color:#6d28d9">{{ $page }}</span></li>
          @else
            <li class="page-item"><a class="page-link" href="{{ $url }}" style="border-radius:50px;margin:0 2px;color:#6d28d9">{{ $page }}</a></li>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" style="border-radius:50px;margin:0 2px">›</a></li>
    @else
      <li class="page-item disabled"><span class="page-link" style="border-radius:50px;margin:0 2px">›</span></li>
    @endif
  </ul>
</nav>
@endif
