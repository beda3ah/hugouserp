<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;

    public function getActiveUsers(): Collection;

    public function getUsersByBranch(int $branchId): Collection;

    public function getUsersWithRoles(): Collection;

    public function paginateWithFilters(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function syncRoles(User $user, array $roles): User;

    public function syncBranches(User $user, array $branchIds): User;

    public function updateLastLogin(User $user): User;

    public function deactivate(User $user): User;

    public function activate(User $user): User;
}
