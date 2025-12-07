<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Currency Exchange Rates') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Manage currency conversion rates for multi-currency support') }}</p>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center gap-2">
            <x-icon name="plus" class="w-5 h-5" />
            {{ __('Add Rate') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Currency Converter Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Quick Convert') }}</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Amount') }}</label>
                    <input type="number" wire:model="convertAmount" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('From') }}</label>
                        <select wire:model="baseCurrency" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('To') }}</label>
                        <select wire:model="convertTo" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            @foreach($currencies as $code => $name)
                                <option value="{{ $code }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <button wire:click="convert" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    {{ __('Convert') }}
                </button>
                
                @if($convertedResult !== null)
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Result') }}</div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($convertedResult, 2) }} {{ $convertTo }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rates Table -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Exchange Rates') }}</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('From') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('To') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Rate') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Effective Date') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($rates as $rate)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $rate->from_currency }}</span>
                                    <span class="text-gray-500 text-xs block">{{ $currencies[$rate->from_currency] ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $rate->to_currency }}</span>
                                    <span class="text-gray-500 text-xs block">{{ $currencies[$rate->to_currency] ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3 font-mono text-gray-900 dark:text-white">
                                    {{ number_format($rate->rate, 6) }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ $rate->effective_date->format('Y-m-d') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($rate->is_active)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $rate->id }})" class="text-gray-400 hover:text-blue-500">
                                            <x-icon name="pencil" class="w-4 h-4" />
                                        </button>
                                        @if($rate->is_active)
                                            <button wire:click="deactivate({{ $rate->id }})" 
                                                    wire:confirm="{{ __('Deactivate this rate?') }}"
                                                    class="text-gray-400 hover:text-red-500">
                                                <x-icon name="x-circle" class="w-4 h-4" />
                                            </button>
                                        @else
                                            <button wire:click="activate({{ $rate->id }})" class="text-gray-400 hover:text-green-500">
                                                <x-icon name="check-circle" class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No exchange rates configured. Add your first rate to enable multi-currency support.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $rates->links() }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                
                <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        {{ $editingId ? __('Edit Exchange Rate') : __('Add Exchange Rate') }}
                    </h3>
                    
                    <form wire:submit="save" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('From Currency') }}</label>
                                <select wire:model="fromCurrency" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $code }} - {{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('fromCurrency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('To Currency') }}</label>
                                <select wire:model="toCurrency" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    @foreach($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $code }} - {{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('toCurrency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Exchange Rate') }}</label>
                            <input type="number" wire:model="rate" step="0.000001" min="0.000001"
                                   placeholder="{{ __('e.g., 30.85 for 1 USD = 30.85 EGP') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <p class="text-xs text-gray-500 mt-1">1 {{ $fromCurrency }} = {{ $rate ?: '?' }} {{ $toCurrency }}</p>
                            @error('rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Effective Date') }}</label>
                            <input type="date" wire:model="effectiveDate"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            @error('effectiveDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <button type="button" wire:click="addReverseRate" 
                                    class="text-sm text-emerald-600 hover:text-emerald-700">
                                {{ __('+ Add Reverse Rate') }}
                            </button>
                            
                            <div class="flex gap-3">
                                <button type="button" wire:click="closeModal" 
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                    {{ $editingId ? __('Update') : __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
