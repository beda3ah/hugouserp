{{-- resources/views/livewire/admin/units-of-measure/index.blade.php --}}
@section('page-header')
    <h1 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <span class="text-2xl">📏</span>
        {{ __('Units of Measure') }}
    </h1>
    <p class="text-sm text-slate-500">{{ __('Manage measurement units for products') }}</p>
@endsection

@section('page-actions')
    <button wire:click="openModal" class="erp-btn-primary">
        <svg class="w-5 h-5 ltr:mr-1 rtl:ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        {{ __('Add Unit') }}
    </button>
@endsection

<div class="space-y-4">
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="relative w-full sm:w-80">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="{{ __('Search units...') }}"
                   class="erp-input ltr:pl-10 rtl:pr-10">
            <svg class="absolute ltr:left-3 rtl:right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Symbol') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Base Unit') }}</th>
                    <th>{{ __('Conversion') }}</th>
                    <th class="text-center">{{ __('Products') }}</th>
                    <th class="text-center">{{ __('Status') }}</th>
                    <th class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                    <tr wire:key="unit-{{ $unit->id }}">
                        <td class="font-medium">
                            {{ $unit->name }}
                            @if($unit->is_base_unit)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ltr:ml-1 rtl:mr-1">
                                    {{ __('Base') }}
                                </span>
                            @endif
                        </td>
                        <td class="font-mono text-sm">{{ $unit->symbol }}</td>
                        <td>{{ $unitTypes[$unit->type] ?? $unit->type }}</td>
                        <td>{{ $unit->baseUnit?->name ?: '-' }}</td>
                        <td class="font-mono text-sm">
                            @if(!$unit->is_base_unit && $unit->baseUnit)
                                1 {{ $unit->symbol }} = {{ number_format($unit->conversion_factor, $unit->decimal_places) }} {{ $unit->baseUnit->symbol }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                {{ $unit->products_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button wire:click="toggleActive({{ $unit->id }})" 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $unit->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $unit->is_active ? __('Active') : __('Inactive') }}
                            </button>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button wire:click="edit({{ $unit->id }})" 
                                        class="erp-btn-icon" 
                                        title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $unit->id }})" 
                                        wire:confirm="{{ __('Are you sure you want to delete this unit?') }}"
                                        class="erp-btn-icon text-red-500 hover:text-red-700 hover:bg-red-50"
                                        title="{{ __('Delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            {{ __('No units found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $units->links() }}
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:click.self="closeModal">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <h3 class="text-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ $editingId ? __('Edit Unit') : __('Add Unit') }}
                    </h3>
                </div>
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="erp-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="erp-input mt-1" placeholder="{{ __('e.g., Kilogram') }}">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="erp-label">{{ __('Arabic Name') }}</label>
                            <input type="text" wire:model="nameAr" class="erp-input mt-1" dir="rtl" placeholder="{{ __('مثال: كيلوجرام') }}">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="erp-label">{{ __('Symbol') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="symbol" class="erp-input mt-1" placeholder="{{ __('e.g., kg') }}">
                            @error('symbol') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="erp-label">{{ __('Type') }}</label>
                            <select wire:model="type" class="erp-input mt-1">
                                @foreach($unitTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="isBaseUnit" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ __('This is a base unit') }}</span>
                        </label>
                    </div>
                    
                    @if(!$isBaseUnit)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="erp-label">{{ __('Base Unit') }}</label>
                                <select wire:model="baseUnitId" class="erp-input mt-1">
                                    <option value="">{{ __('Select base unit') }}</option>
                                    @foreach($baseUnits as $baseUnit)
                                        @if($baseUnit->id !== $editingId)
                                            <option value="{{ $baseUnit->id }}">{{ $baseUnit->name }} ({{ $baseUnit->symbol }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="erp-label">{{ __('Conversion Factor') }}</label>
                                <input type="number" wire:model="conversionFactor" step="0.000001" min="0.000001" class="erp-input mt-1">
                                <p class="text-xs text-slate-500 mt-1">{{ __('How many base units equal 1 of this unit') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="erp-label">{{ __('Decimal Places') }}</label>
                            <input type="number" wire:model="decimalPlaces" min="0" max="6" class="erp-input mt-1">
                        </div>
                        <div>
                            <label class="erp-label">{{ __('Sort Order') }}</label>
                            <input type="number" wire:model="sortOrder" min="0" class="erp-input mt-1">
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isActive" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-700">{{ __('Active') }}</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" wire:click="closeModal" class="erp-btn-secondary">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="erp-btn-primary">
                            {{ $editingId ? __('Save Changes') : __('Create Unit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
