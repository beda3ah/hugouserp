<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Login Activity') }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ __('Monitor user login and authentication events') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['total_logins']) }}</div>
            <div class="text-sm text-green-600 dark:text-green-400">{{ __('Total Logins (30d)') }}</div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800">
            <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($stats['failed_attempts']) }}</div>
            <div class="text-sm text-red-600 dark:text-red-400">{{ __('Failed Attempts (30d)') }}</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($stats['unique_users']) }}</div>
            <div class="text-sm text-blue-600 dark:text-blue-400">{{ __('Unique Users') }}</div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($stats['unique_ips']) }}</div>
            <div class="text-sm text-purple-600 dark:text-purple-400">{{ __('Unique IPs') }}</div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('Search by email, user, or IP...') }}"
                class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            
            <select wire:model.live="event" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">{{ __('All Events') }}</option>
                <option value="login">{{ __('Login') }}</option>
                <option value="logout">{{ __('Logout') }}</option>
                <option value="failed">{{ __('Failed') }}</option>
                <option value="lockout">{{ __('Lockout') }}</option>
            </select>

            <input type="date" wire:model.live="dateFrom" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <input type="date" wire:model.live="dateTo" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Event') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('IP Address') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Device') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($activities as $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $activity->user?->name ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $activity->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $eventColors = [
                                    'login' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'logout' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    'lockout' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'password_reset' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $eventColors[$activity->event] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ __(ucfirst($activity->event)) }}
                            </span>
                            @if($activity->failure_reason)
                                <div class="text-xs text-red-500 mt-1">{{ $activity->failure_reason }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ $activity->ip_address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                            <div class="text-sm">{{ $activity->browser }}</div>
                            <div class="text-xs">{{ $activity->platform }} / {{ $activity->device_type }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                            {{ $activity->created_at->format('Y-m-d H:i:s') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No activity found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $activities->links() }}
        </div>
    </div>
</div>
