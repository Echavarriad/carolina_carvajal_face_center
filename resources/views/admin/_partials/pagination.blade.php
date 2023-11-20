@if ($paginator->hasPages())
    <ul class="pagination">
         @if (!$paginator->onFirstPage())
         		<li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="fas fa-arrow-left"></i></a></li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                   <li><a>...</a></li>
            @endif
            @if (is_array($element))                
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                     <li class="active page-item"><a class="page-link">{{ $page }}</a></li>
                    @else
  									<li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
  					<li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-arrow-right"></i></a></li>
        @endif
    </ul>
@endif