<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function findByName(string $name, string $guardName = 'web'): ?Role;

    public function getAllRoles(string $guardName = 'web'): Collection;

    public function getRolesWithPermissions(): Collection;

    public function paginateWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function syncPermissions(Role $role, array $permissions): Role;

    public function getUsersWithRole(Role $role): Collection;

    public function createWithPermissions(array $data, array $permissions = []): Role;

    public function updateWithPermissions(Role $role, array $data, array $permissions = []): Role;
}
