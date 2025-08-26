<?php

namespace App\Policies;

use App\Enums\PermissionType;
use App\Models\Employee;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Employee $user): bool
    {
        return $user->hasPermissionTo(PermissionType::Employee->value) || $user->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Employee $user, Employee $employee): bool
    {
        return $user->hasPermissionTo(PermissionType::Employee->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Employee $user): bool
    {
        return $user->hasPermissionTo(PermissionType::Employee->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $user, Employee $employee): bool
    {
        return ($user->hasPermissionTo(PermissionType::Employee->value) || $user->hasPermissionTo(PermissionType::RolePermission->value));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Employee $user, Employee $employee): bool
    {
        return $user->hasPermissionTo(PermissionType::Employee->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Employee $user, Employee $employee): bool
    {
        return $user->hasPermissionTo(PermissionType::Employee->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Employee $user, Employee $employee): bool
    {
        return $user->hasPermissionTo(PermissionType::Employee->value);
    }
}