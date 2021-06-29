@if ($paginator->hasPages())

    {{-- <ul class="pagination pagination-sm m-0 float-right">
        <li class="page-item"><a class="page-link" href="#">«</a></li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">»</a></li>
    </ul> --}}
    <ul class="pagination pagination-sm m-0 float-right btn-group">

        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><a class="page-link" href="#">← Previous</a></li>
        @else
            <li class="page-item">
                <a href="{{ $paginator->previousPageUrl() . "&" . http_build_query(Request::only(['name','email','phone','type','cat_name','owner','from','to','unique_number'])) }}" class="page-link" rel="prev">
                    ← Previous
                </a>
            </li>
        @endif


        {{-- {{dd($elements)}} --}}
        @foreach ($elements as $element)

            @if (is_string($element))
                <li class="page-item disabled"><a class="page-link" href="#">{{ $element }}</a></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active disabled">
                            <a class="page-link" href={{ $url }}>{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item"><a class="page-link"
                                href={{ $url . "&" . http_build_query(Request::only(['name','email','phone','type','cat_name','owner','from','to','unique_number'])) }}>{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() . "&" . http_build_query(Request::all(['name','email','phone','type','cat_name','owner','from','to','unique_number'])) }}" rel="next">Next →</a>
            </li>
        @else
            <li class="page-item disabled"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next
                    →</a></li>
        @endif
    </ul>
@endif
