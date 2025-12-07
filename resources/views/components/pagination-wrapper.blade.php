@props([
    'paginator' => null,
    'mode' => 'traditional',
    'loadMoreMethod' => 'loadMore',
    'hasMore' => null,
])

@if($paginator)
    @if($mode === 'load-more' && $hasMore !== null)
        <x-load-more :hasMore="$hasMore" :loadMoreMethod="$loadMoreMethod" />
    @elseif($mode === 'infinite' && $hasMore !== null)
        <x-load-more :hasMore="$hasMore" :loadMoreMethod="$loadMoreMethod" :infiniteScroll="true" />
    @else
        <div class="mt-4">
            {{ $paginator->links() }}
        </div>
    @endif
@elseif($hasMore !== null)
    @if($mode === 'infinite')
        <x-load-more :hasMore="$hasMore" :loadMoreMethod="$loadMoreMethod" :infiniteScroll="true" />
    @else
        <x-load-more :hasMore="$hasMore" :loadMoreMethod="$loadMoreMethod" />
    @endif
@endif
