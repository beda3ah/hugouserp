<nav class="flex mb-4 text-sm text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-emerald-600">
                <span class="mdi mdi-view-dashboard-outline mr-1"></span>
                Dashboard
            </a>
        </li>
        @isset($items)
            @foreach($items as $item)
                <li>
                    <div class="flex items-center">
                        <span class="mx-2 text-slate-400">/</span>
                        @if(isset($item['url']))
                            <a href="{{ $item['url'] }}" class="hover:text-emerald-600">
                                {{ $item['label'] ?? '' }}
                            </a>
                        @else
                            <span class="font-medium text-slate-700 dark:text-slate-200">{{ $item['label'] ?? '' }}</span>
                        @endif
                    </div>
                </li>
            @endforeach
        @endisset
    </ol>
</nav>
