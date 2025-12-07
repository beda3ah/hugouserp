<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('User Preferences') }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ __('Customize your experience') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Appearance') }}</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Theme') }}</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="theme" value="light" class="text-emerald-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Light') }}</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="theme" value="dark" class="text-emerald-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Dark') }}</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model="theme" value="system" class="text-emerald-600">
                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('System') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Session Settings') }}</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Session Timeout (minutes)') }}</label>
                    <input type="number" wire:model="session_timeout" min="5" max="480" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="auto_logout" class="rounded border-gray-300 text-emerald-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Auto-logout on inactivity') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Dashboard Widgets') }}</h2>
            
            <div class="grid grid-cols-2 gap-3">
                @foreach($availableWidgets as $key => $label)
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:click="toggleWidget('{{ $key }}')" 
                        @checked($dashboard_widgets[$key] ?? false) 
                        class="rounded border-gray-300 text-emerald-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('POS Keyboard Shortcuts') }}</h2>
            
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @foreach(['F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12'] as $key)
                <div class="flex items-center gap-3">
                    <span class="w-12 px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-center text-sm font-mono">{{ $key }}</span>
                    <select wire:model="pos_shortcuts.{{ $key }}" class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">{{ __('Not assigned') }}</option>
                        @foreach($availableActions as $actionKey => $actionLabel)
                        <option value="{{ $actionKey }}">{{ $actionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Notifications') }}</h2>
            
            <div class="space-y-3">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:click="toggleNotification('low_stock')" 
                        @checked($notification_settings['low_stock'] ?? false) 
                        class="rounded border-gray-300 text-emerald-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Low Stock Alerts') }}</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:click="toggleNotification('new_orders')" 
                        @checked($notification_settings['new_orders'] ?? false) 
                        class="rounded border-gray-300 text-emerald-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('New Orders') }}</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" wire:click="toggleNotification('payment_due')" 
                        @checked($notification_settings['payment_due'] ?? false) 
                        class="rounded border-gray-300 text-emerald-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Payment Reminders') }}</span>
                </label>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ __('Printing') }}</h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Default Printer') }}</label>
                <input type="text" wire:model="default_printer" placeholder="{{ __('e.g., POS-Printer-1') }}" 
                    class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <p class="mt-1 text-xs text-gray-500">{{ __('Enter the printer name for thermal printing') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-between">
        <button wire:click="resetToDefaults" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            {{ __('Reset to Defaults') }}
        </button>
        <button wire:click="save" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
            {{ __('Save Preferences') }}
        </button>
    </div>
</div>
