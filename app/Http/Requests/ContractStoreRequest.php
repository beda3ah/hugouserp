<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('rental.contracts.create') ?? false;
    }

    public function rules(): array
    {
        $branchId = $this->route('branch')?->id;

        $unitValidation = ['required', 'exists:rental_units,id'];
        if ($branchId) {
            $unitValidation[] = function ($attribute, $value, $fail) use ($branchId) {
                $unitExists = \App\Models\RentalUnit::where('id', $value)
                    ->whereHas('property', function ($q) use ($branchId) {
                        $q->where('branch_id', $branchId);
                    })
                    ->exists();

                if (! $unitExists) {
                    $fail(__('The selected unit does not belong to this branch.'));
                }
            };
        }

        $tenantValidation = ['required', 'exists:tenants,id'];
        if ($branchId) {
            $tenantValidation[] = function ($attribute, $value, $fail) use ($branchId) {
                $tenantExists = \App\Models\Tenant::where('id', $value)
                    ->where('branch_id', $branchId)
                    ->exists();

                if (! $tenantExists) {
                    $fail(__('The selected tenant does not belong to this branch.'));
                }
            };
        }

        return [
            'unit_id' => $unitValidation,
            'tenant_id' => $tenantValidation,
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'rent' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
