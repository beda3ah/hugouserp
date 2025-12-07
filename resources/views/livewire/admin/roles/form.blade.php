<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $editMode ? __('Edit Role') : __('Add Role') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Configure role name and permissions') }}</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="erp-btn erp-btn-secondary">{{ __('Back') }}</a>
    </div>

    @if(session()->has('error'))
        <div class="p-3 bg-red-50 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    <form wire:submit="save" class="erp-card p-6 space-y-6">
        <div>
            <label class="erp-label">{{ __('Role Name') }} <span class="text-red-500">*</span></label>
            <input type="text" wire:model="name" class="erp-input max-w-md @error('name') border-red-500 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="erp-label mb-4">{{ __('Permissions') }}</label>
            <div class="space-y-6">
                @foreach($permissions as $group => $groupPermissions)
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <div class="bg-slate-50 px-4 py-2 font-medium text-slate-700 border-b">
                            {{ __('permission_group.' . $group, [], 'ar') !== 'permission_group.' . $group ? __('permission_group.' . $group) : ucfirst($group) }}
                        </div>
                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($groupPermissions as $permission)
                                <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-slate-50 p-2 rounded">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span>{{ __('permission.' . $permission->name, [], 'ar') !== 'permission.' . $permission->name ? __('permission.' . $permission->name) : $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.roles.index') }}" class="erp-btn erp-btn-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="erp-btn erp-btn-primary">{{ $editMode ? __('Update') : __('Save') }}</button>
        </div>
    </form>
</div>
