<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $editMode ? __('Edit Supplier') : __('Add Supplier') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Fill in the supplier details below') }}</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="erp-btn erp-btn-secondary">{{ __('Back') }}</a>
    </div>

    <form wire:submit="save" class="erp-card p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="erp-label">{{ __('Supplier Name') }} <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" class="erp-input @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="erp-label">{{ __('Company Name') }}</label>
                <input type="text" wire:model="company_name" class="erp-input">
            </div>

            <div>
                <label class="erp-label">{{ __('Email') }}</label>
                <input type="email" wire:model="email" class="erp-input">
            </div>

            <div>
                <label class="erp-label">{{ __('Phone') }}</label>
                <input type="text" wire:model="phone" dir="ltr" class="erp-input">
            </div>

            <div>
                <label class="erp-label">{{ __('Contact Person') }}</label>
                <input type="text" wire:model="contact_person" class="erp-input">
            </div>

            <div>
                <label class="erp-label">{{ __('Tax Number') }}</label>
                <input type="text" wire:model="tax_number" class="erp-input">
            </div>

            <div>
                <label class="erp-label">{{ __('City') }}</label>
                <input type="text" wire:model="city" class="erp-input">
            </div>

            <div>
                <label class="erp-label">{{ __('Country') }}</label>
                <input type="text" wire:model="country" class="erp-input">
            </div>

            <div class="md:col-span-2">
                <label class="erp-label">{{ __('Address') }}</label>
                <textarea wire:model="address" rows="2" class="erp-input"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="erp-label">{{ __('Notes') }}</label>
                <textarea wire:model="notes" rows="3" class="erp-input"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="is_active" class="text-sm text-slate-700">{{ __('Active') }}</label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('suppliers.index') }}" class="erp-btn erp-btn-secondary">{{ __('Cancel') }}</a>
            <button type="submit" class="erp-btn erp-btn-primary">{{ $editMode ? __('Update') : __('Save') }}</button>
        </div>
    </form>
</div>
