<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Module;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ModuleRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Module;

    public function findByCode(string $code): ?Module;

    public function getActiveModules(): Collection;

    public function getModulesForBranch(int $branchId): Collection;

    public function paginateWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function getModuleWithFields(int $moduleId): ?Module;

    public function syncBranches(Module $module, array $branchIds): Module;

    public function deactivate(Module $module): Module;

    public function activate(Module $module): Module;

    public function getModulePermissions(Module $module): array;
}
