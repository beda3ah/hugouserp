@props([
    'hasMore' => true,
    'loading' => false,
    'loadMoreMethod' => 'loadMore',
    'infiniteScroll' => false,
])

@if($hasMore)
    <div 
        class="flex justify-center py-4"
        @if($infiniteScroll)
            x-data="{
                init() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                @this.call('{{ $loadMoreMethod }}');
                            }
                        });
                    }, { rootMargin: '100px' });
                    observer.observe($el);
                }
            }"
        @endif
    >
        <button
            type="button"
            wire:click="{{ $loadMoreMethod }}"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-50 cursor-not-allowed"
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm"
        >
            <span wire:loading.remove wire:target="{{ $loadMoreMethod }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
            <span wire:loading wire:target="{{ $loadMoreMethod }}">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </span>
            <span wire:loading.remove wire:target="{{ $loadMoreMethod }}">{{ __('Load More') }}</span>
            <span wire:loading wire:target="{{ $loadMoreMethod }}">{{ __('Loading...') }}</span>
        </button>
    </div>
@else
    <div class="flex justify-center py-4">
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 text-xs text-slate-500">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ __('All items loaded') }}
        </span>
    </div>
@endif
