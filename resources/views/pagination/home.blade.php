@if ($paginator->hasPages())
<nav aria-label="...">
    <ul class="pagination">
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{$paginator->url($paginator->currentPage()-1)}}" tabindex="-1">Previous</a>
        </li>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li><a>{{ $element }}</a></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link" href="#">{{$page}} <span class="sr-only">(current)</span></a>
                        </li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{$paginator->url($page)}}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{$paginator->url($paginator->currentPage()+1)}}">Next</a>
        </li>
        @else
        <li class="page-item disabled">
            <a class="page-link" href="#">Next</a>
        </li>
        @endif
    </ul>
</nav>
<!--<ul class="shop-pagination box-shadow text-center ptblr-10-30">
    @if ($paginator->onFirstPage())
    <li class="disabled"><a class="disabled"><i class="zmdi zmdi-chevron-left"></i></a></li>
    @else
    <li><a href="javascript:;" onclick="productSearch('{{(int)$paginator->currentPage()-1}}')" rel="prev"><i class="zmdi zmdi-chevron-left"></i></a></li>
    @endif

    @foreach ($elements as $element)

    @if (is_string($element))
    <li><a>{{ $element }}</a></li>
    @endif



    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li class="active"><a>{{ $page }}</a></li>
    @else
    <li><a href="javascript:;" onclick="productSearch('{{$page}}')">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach
    @if ($paginator->hasMorePages())
    <li><a href="javascript:;" onclick="productSearch('{{(int)$paginator->currentPage()+1}}')" rel="next"><i class="zmdi zmdi-chevron-right"></i></a></li>
    @else
    <li><a><i class="zmdi zmdi-chevron-right"></i></a></li>
    @endif
</ul>-->
@endif