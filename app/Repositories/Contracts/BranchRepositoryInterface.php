<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BranchRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCode(string $code): ?Branch;

    public function getActiveBranches(): Collection;

    public function getBranchesWithModules(): Collection;

    public function paginateWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function syncModules(Branch $branch, array $moduleIds): Branch;

    public function getEnabledModules(Branch $branch): Collection;

    public function deactivate(Branch $branch): Branch;

    public function activate(Branch $branch): Branch;

    public function getBranchSettings(Branch $branch): array;

    public function updateSettings(Branch $branch, array $settings): Branch;
}
