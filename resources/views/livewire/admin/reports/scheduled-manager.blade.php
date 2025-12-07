<div class="space-y-6" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-lg md:text-xl font-semibold text-slate-800">
                {{ __('Scheduled reports') }}
            </h1>
            <p class="text-sm text-slate-500">
                {{ __('Create, edit, and delete automatic email schedules for your reports.') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="xl:col-span-2 space-y-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-800 mb-3">
                    {{ __('Scheduled reports list') }}
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs md:text-sm">
                        <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-[11px] font-medium text-slate-500">#</th>
                            <th class="px-3 py-2 text-left text-[11px] font-medium text-slate-500">{{ __('Template') }}</th>
                            <th class="px-3 py-2 text-left text-[11px] font-medium text-slate-500">{{ __('Route') }}</th>
                            <th class="px-3 py-2 text-left text-[11px] font-medium text-slate-500">{{ __('Cron') }}</th>
                            <th class="px-3 py-2 text-left text-[11px] font-medium text-slate-500">{{ __('User') }}</th>
                            <th class="px-3 py-2 text-left text-[11px] font-medium text-slate-500">{{ __('Recipient') }}</th>
                            <th class="px-3 py-2 text-right text-[11px] font-medium text-slate-500">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                        @forelse($reports as $report)
                            @php
                                $tpl = $report->template;
                            @endphp
                            <tr>
                                <td class="px-3 py-1.5">{{ $report->id }}</td>
                                <td class="px-3 py-1.5">
                                    @if($tpl)
                                        <div class="flex flex-col">
                                            <span class="font-medium text-[11px] text-slate-800">{{ $tpl->name }}</span>
                                            <span class="text-[10px] text-slate-400">{{ strtoupper($tpl->output_type) }}</span>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400">{{ __('Custom') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-1.5">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-[11px] text-slate-800">{{ $report->route_name }}</span>
                                        @php
                                            $route = collect($availableRoutes)->firstWhere('name', $report->route_name);
                                        @endphp
                                        @if($route)
                                            <span class="text-[10px] text-slate-400">/{{ $route['uri'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-1.5 text-[11px] text-slate-700">{{ $report->cron_expression }}</td>
                                <td class="px-3 py-1.5 text-[11px] text-slate-700">
                                    {{ $report->user?->name ?? ('#'.$report->user_id) }}
                                </td>
                                <td class="px-3 py-1.5 text-[11px] text-slate-700">
                                    {{ $report->recipient_email ?? $report->user?->email }}
                                </td>
                                <td class="px-3 py-1.5 text-right">
                                    <button type="button" wire:click="edit({{ $report->id }})"
                                            class="text-[11px] text-indigo-600 hover:text-indigo-700 mr-2">
                                        {{ __('Edit') }}
                                    </button>
                                    <button type="button" wire:click="delete({{ $report->id }})"
                                            class="text-[11px] text-red-500 hover:text-red-600">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-3 text-center text-xs text-slate-500">
                                    {{ __('No scheduled reports yet.') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-800 mb-3">
                    {{ $editingId ? __('Edit schedule') : __('New schedule') }}
                </h2>

                <div class="space-y-3 text-xs md:text-sm">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-0.5">
                            {{ __('Template (optional)') }}
                        </label>
                        <select wire:model="templateId" wire:change="applyTemplate"
                                class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs">
                            <option value="">{{ __('Custom route') }}</option>
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl['id'] }}">
                                    {{ $tpl['name'] }} ({{ strtoupper($tpl['output_type']) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-0.5">
                            {{ __('Route') }}
                        </label>
                        <select wire:model="routeName"
                                class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs">
                            <option value="">{{ __('Select report route') }}</option>
                            @foreach($availableRoutes as $route)
                                <option value="{{ $route['name'] }}">
                                    {{ $route['name'] }} (/{{ $route['uri'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('routeName')
                        <p class="mt-0.5 text-[11px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-0.5">
                                {{ __('Cron expression') }}
                            </label>
                            <input type="text" wire:model="cronExpression"
                                   class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs">
                            @error('cronExpression')
                            <p class="mt-0.5 text-[11px] text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-0.5 text-[10px] text-slate-400">
                                {{ __('For example: 0 8 * * *') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-0.5">
                                {{ __('User') }}
                            </label>
                            <select wire:model="userId"
                                    class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs">
                                <option value="">{{ __('Current user') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('userId')
                            <p class="mt-0.5 text-[11px] text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-0.5">
                            {{ __('Recipient email (optional)') }}
                        </label>
                        <input type="email" wire:model="recipientEmail"
                               class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs">
                        @error('recipientEmail')
                        <p class="mt-0.5 text-[11px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-medium text-slate-500 mb-0.5">
                            {{ __('Filters (JSON)') }}
                        </label>
                        <textarea wire:model="filtersJson" rows="5"
                                  class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs font-mono"></textarea>
                        @error('filtersJson')
                        <p class="mt-0.5 text-[11px] text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-0.5 text-[10px] text-slate-400">
                            {{ __('Example: {"branch_id":1,"from":"2025-01-01"}') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <button type="button" wire:click="createNew"
                                class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                            {{ __('Reset') }}
                        </button>
                        <button type="button" wire:click="save"
                                class="inline-flex items-center rounded-lg border border-indigo-500 bg-indigo-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-600">
                            {{ $editingId ? __('Save changes') : __('Create schedule') }}
                        </button>
                    </div>
                </div>
            </div>

            <div wire:offline.class="block" class="hidden rounded-2xl border border-amber-200 bg-amber-50 p-3 text-[11px] text-amber-800">
                {{ __('You appear to be offline. Changes will be synced when you are back online.') }}
            </div>
        </div>
    </div>
</div>
