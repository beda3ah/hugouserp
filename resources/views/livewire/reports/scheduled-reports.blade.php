<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Scheduled Reports') }}</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Automate report generation and delivery') }}</p>
        </div>
        <button wire:click="openModal" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center gap-2">
            <x-icon name="plus" class="w-5 h-5" />
            {{ __('New Schedule') }}
        </button>
    </div>

    <!-- Schedules Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Schedule Name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Report Template') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Frequency') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Time') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Format') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Last Run') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $schedule->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                {{ $schedule->template_name ?? __('Unknown') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full capitalize">
                                    {{ $schedule->frequency }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 font-mono text-sm">
                                {{ $schedule->time_of_day }}
                                @if($schedule->frequency === 'weekly')
                                    <span class="text-xs text-gray-400">({{ ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$schedule->day_of_week ?? 0] }})</span>
                                @elseif($schedule->frequency === 'monthly')
                                    <span class="text-xs text-gray-400">({{ __('Day') }} {{ $schedule->day_of_month }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 uppercase text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ $schedule->format }}
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleActive({{ $schedule->id }})" class="focus:outline-none">
                                    @if($schedule->is_active)
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 rounded-full">
                                            {{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400 rounded-full">
                                            {{ __('Paused') }}
                                        </span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">
                                @if($schedule->last_run_at)
                                    {{ \Carbon\Carbon::parse($schedule->last_run_at)->diffForHumans() }}
                                @else
                                    {{ __('Never') }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="runNow({{ $schedule->id }})" 
                                            class="text-gray-400 hover:text-emerald-500" title="{{ __('Run Now') }}">
                                        <x-icon name="play" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="edit({{ $schedule->id }})" class="text-gray-400 hover:text-blue-500">
                                        <x-icon name="pencil" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="delete({{ $schedule->id }})" 
                                            wire:confirm="{{ __('Are you sure you want to delete this schedule?') }}"
                                            class="text-gray-400 hover:text-red-500">
                                        <x-icon name="trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <x-icon name="calendar" class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" />
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">{{ __('No Scheduled Reports') }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('Create a schedule to automatically generate and send reports') }}</p>
                                    <button wire:click="openModal" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                        {{ __('Create Schedule') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="closeModal"></div>
                
                <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        {{ $editingId ? __('Edit Schedule') : __('New Scheduled Report') }}
                    </h3>
                    
                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Schedule Name') }} *</label>
                            <input type="text" wire:model="scheduleName" 
                                   placeholder="{{ __('e.g., Daily Sales Report') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            @error('scheduleName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Report Template') }} *</label>
                            <select wire:model="templateId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                <option value="">{{ __('Select a template...') }}</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                            @error('templateId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Frequency') }} *</label>
                                <select wire:model.live="frequency" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="daily">{{ __('Daily') }}</option>
                                    <option value="weekly">{{ __('Weekly') }}</option>
                                    <option value="monthly">{{ __('Monthly') }}</option>
                                    <option value="quarterly">{{ __('Quarterly') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Time') }} *</label>
                                <input type="time" wire:model="timeOfDay" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        @if($frequency === 'weekly')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Day of Week') }}</label>
                                <select wire:model="dayOfWeek" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="0">{{ __('Sunday') }}</option>
                                    <option value="1">{{ __('Monday') }}</option>
                                    <option value="2">{{ __('Tuesday') }}</option>
                                    <option value="3">{{ __('Wednesday') }}</option>
                                    <option value="4">{{ __('Thursday') }}</option>
                                    <option value="5">{{ __('Friday') }}</option>
                                    <option value="6">{{ __('Saturday') }}</option>
                                </select>
                            </div>
                        @endif

                        @if(in_array($frequency, ['monthly', 'quarterly']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Day of Month') }}</label>
                                <select wire:model="dayOfMonth" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    @for($i = 1; $i <= 28; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Recipient Emails') }} *</label>
                            <textarea wire:model="recipientEmails" rows="2"
                                      placeholder="{{ __('email1@example.com, email2@example.com') }}"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Separate multiple emails with commas') }}</p>
                            @error('recipientEmails') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Output Format') }}</label>
                                <select wire:model="format" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <div class="flex items-center pt-6">
                                <input type="checkbox" wire:model="isActive" id="is_active" 
                                       class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Active') }}</label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="closeModal" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                {{ $editingId ? __('Update') : __('Create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
