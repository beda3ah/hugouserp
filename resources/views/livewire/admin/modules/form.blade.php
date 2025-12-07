<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $editMode ? __('Edit Module') : __('Add Module') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Configure module settings and custom fields') }}</p>
        </div>
        <a href="{{ route('admin.modules.index') }}" class="erp-btn erp-btn-secondary">{{ __('Back') }}</a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="erp-card p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">{{ __('Basic Information') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="erp-label">{{ __('Module Key') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="key" class="erp-input @error('key') border-red-500 @enderror" placeholder="e.g., hrm, inventory">
                    @error('key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="erp-label">{{ __('Sort Order') }}</label>
                    <input type="number" wire:model="sort_order" class="erp-input" min="0">
                </div>

                <div>
                    <label class="erp-label">{{ __('Name (English)') }} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" class="erp-input @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="erp-label">{{ __('Name (Arabic)') }}</label>
                    <input type="text" wire:model="name_ar" dir="rtl" class="erp-input">
                </div>

                <div>
                    <label class="erp-label">{{ __('Icon') }}</label>
                    <input type="text" wire:model="icon" class="erp-input" placeholder="📦">
                </div>

                <div>
                    <label class="erp-label">{{ __('Color') }}</label>
                    <select wire:model="color" class="erp-input">
                        <option value="emerald">{{ __('Emerald') }}</option>
                        <option value="blue">{{ __('Blue') }}</option>
                        <option value="purple">{{ __('Purple') }}</option>
                        <option value="red">{{ __('Red') }}</option>
                        <option value="amber">{{ __('Amber') }}</option>
                        <option value="cyan">{{ __('Cyan') }}</option>
                        <option value="pink">{{ __('Pink') }}</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="erp-label">{{ __('Description (English)') }}</label>
                    <textarea wire:model="description" rows="2" class="erp-input"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="erp-label">{{ __('Description (Arabic)') }}</label>
                    <textarea wire:model="description_ar" rows="2" dir="rtl" class="erp-input"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-slate-300 text-emerald-600">
                    <label for="is_active" class="text-sm">{{ __('Active') }}</label>
                </div>
            </div>
        </div>

        <div class="erp-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-slate-800">{{ __('Custom Fields') }}</h2>
                <button type="button" wire:click="addCustomField" class="erp-btn erp-btn-secondary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('Add Field') }}
                </button>
            </div>

            @if(count($customFields) > 0)
                <div class="space-y-4">
                    @foreach($customFields as $index => $field)
                        <div class="border border-slate-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <span class="text-sm font-medium text-slate-600">{{ __('Field') }} #{{ $index + 1 }}</span>
                                <button type="button" wire:click="removeCustomField({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="erp-label text-xs">{{ __('Field Key') }}</label>
                                    <input type="text" wire:model="customFields.{{ $index }}.field_key" class="erp-input text-sm" placeholder="e.g., department_id">
                                </div>
                                <div>
                                    <label class="erp-label text-xs">{{ __('Label (English)') }}</label>
                                    <input type="text" wire:model="customFields.{{ $index }}.field_label" class="erp-input text-sm">
                                </div>
                                <div>
                                    <label class="erp-label text-xs">{{ __('Label (Arabic)') }}</label>
                                    <input type="text" wire:model="customFields.{{ $index }}.field_label_ar" dir="rtl" class="erp-input text-sm">
                                </div>
                                <div>
                                    <label class="erp-label text-xs">{{ __('Field Type') }}</label>
                                    <select wire:model="customFields.{{ $index }}.field_type" class="erp-input text-sm">
                                        <option value="text">{{ __('Text') }}</option>
                                        <option value="textarea">{{ __('Textarea') }}</option>
                                        <option value="number">{{ __('Number') }}</option>
                                        <option value="email">{{ __('Email') }}</option>
                                        <option value="phone">{{ __('Phone') }}</option>
                                        <option value="date">{{ __('Date') }}</option>
                                        <option value="datetime">{{ __('Date & Time') }}</option>
                                        <option value="select">{{ __('Dropdown') }}</option>
                                        <option value="checkbox">{{ __('Checkbox') }}</option>
                                        <option value="file">{{ __('File') }}</option>
                                        <option value="image">{{ __('Image') }}</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" wire:model="customFields.{{ $index }}.is_required" class="rounded border-slate-300 text-emerald-600">
                                        {{ __('Required') }}
                                    </label>
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" wire:model="customFields.{{ $index }}.is_active" class="rounded border-slate-300 text-emerald-600">
                                        {{ __('Active') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500 text-center py-4">{{ __('No custom fields. Click "Add Field" to create one.') }}</p>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.modules.index') }}" class="erp-btn erp-btn-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="erp-btn erp-btn-primary">{{ $editMode ? __('Update') : __('Save') }}</button>
        </div>
    </form>
</div>
