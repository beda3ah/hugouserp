<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function findByName(string $name, string $guardName = 'web'): ?Permission;

    public function getAllPermissions(string $guardName = 'web'): Collection;

    public function getPermissionsByModule(string $module): Collection;

    public function getGroupedPermissions(): array;

    public function paginateWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function syncToRole(Permission $permission, array $roleIds): Permission;

    public function createForModule(string $module, array $actions = ['view', 'create', 'edit', 'delete']): Collection;
}
