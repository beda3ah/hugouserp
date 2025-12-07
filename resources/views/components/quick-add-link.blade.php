{{-- resources/views/components/quick-add-link.blade.php --}}
@props([
    'route' => null,
    'label' => null,
    'permission' => null,
    'icon' => 'plus',
    'modal' => null,
    'size' => 'sm'
])

@php
    $user = auth()->user();
    $hasPermission = !$permission || ($user && ($user->hasRole('Super Admin') || $user->can($permission)));
    $sizeClasses = match($size) {
        'xs' => 'text-xs px-1.5 py-0.5',
        'sm' => 'text-xs px-2 py-1',
        'md' => 'text-sm px-2.5 py-1.5',
        default => 'text-xs px-2 py-1'
    };
@endphp

@if($hasPermission)
    @if($modal)
        <button 
            type="button"
            wire:click="{{ $modal }}"
            {{ $attributes->merge([
                'class' => "inline-flex items-center gap-1 {$sizeClasses} font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all duration-200"
            ]) }}
            title="{{ $label ?? __('Add New') }}"
        >
            @if($icon === 'plus')
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            @endif
            <span>{{ $label ?? __('Add New') }}</span>
        </button>
    @elseif($route)
        <a 
            href="{{ $route }}"
            target="_blank"
            {{ $attributes->merge([
                'class' => "inline-flex items-center gap-1 {$sizeClasses} font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all duration-200"
            ]) }}
            title="{{ $label ?? __('Add New') }}"
        >
            @if($icon === 'plus')
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            @endif
            <span>{{ $label ?? __('Add New') }}</span>
            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    @endif
@endif
