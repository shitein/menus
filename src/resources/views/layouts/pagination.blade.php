@if($settings['pagination'] == 1)
<div class="row pagination-row">
    {{-- @if($paginationData->total()>10) --}}
    <div class="col-sm-6">
        <div class="no_of_record mt-1">
            {{--<span class="text-dark">
                    Showing @if(!empty($paginationData->firstItem())){{$paginationData->firstItem()}}@else 0 @endif
            to @if(!empty($paginationData->lastItem())){{$paginationData->lastItem()}}@else 0 @endif of {{$paginationData->total()}}
            &nbsp;&nbsp;Page {{$paginationData->currentPage()}} of {{$paginationData->lastPage()}}
            </span>
            <span class="text-dark"><b>Total Record: </b>{{ $paginationData->total() }}</span>
            --}}
        </div>
    </div>
    {{-- @endif --}}
    <div class="col-sm-6 text-right">
        <div id="pagination" class="">
            <!-- $paginationData->links() -->
            <!-- Swapnil 06-Apr-21 SGWE-128 Pagination responsive change-->
            @if (!empty($paginationData) && $paginationData->hasPages())
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginationData->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="« Previous"><span class="page-link" aria-hidden="true">‹</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $paginationData->previousPageUrl() }}" rel="prev">‹</a></li>
                @endif

                @if($paginationData->currentPage() > 4)
                <li class="page-item"><a class="page-link" href="{{ $paginationData->url(1) }}">1</a></li>
                @endif
                <!-- @if($paginationData->currentPage() > 4) -->
                <!-- <li class="page-item"><span class="page-link">...</span></li> -->
                <!-- @endif -->
                @foreach(range(1, $paginationData->lastPage()) as $i)
                @if($i >= $paginationData->currentPage() - 2 && $i <= $paginationData->currentPage() + 2)
                    @if ($i == $paginationData->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $paginationData->url($i) }}">{{ $i }}</a></li>
                    @endif
                    @endif
                    @endforeach
                    @if($paginationData->currentPage() < $paginationData->lastPage() - 4)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        @if($paginationData->currentPage() < $paginationData->lastPage() - 3)
                            <li class="hidden-xs page-item"><a class="page-link" href="{{ $paginationData->url($paginationData->lastPage()) }}">{{ $paginationData->lastPage() }}</a></li>
                            @endif

                            {{-- Next Page Link --}}
                            @if ($paginationData->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $paginationData->nextPageUrl() }}" rel="next" aria-label="Next »">›</a></li>
                            @else
                            <li class="page-item disabled"><span class="page-link">›</span></li>
                            @endif
            </ul>
            @endif
        </div>
    </div>
</div>
@endif
