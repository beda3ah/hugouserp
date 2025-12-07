<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAllUsers(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function getUserById(int $id): ?User;

    public function createUser(array $data): User;

    public function updateUser(User $user, array $data): User;

    public function deleteUser(User $user): void;

    public function changePassword(User $user, string $newPassword): User;

    public function assignRoles(User $user, array $roles): User;

    public function assignBranches(User $user, array $branchIds): User;

    public function activateUser(User $user): User;

    public function deactivateUser(User $user): User;

    public function getUsersByBranch(int $branchId): Collection;

    public function searchUsers(string $query, int $limit = 10): Collection;
}
