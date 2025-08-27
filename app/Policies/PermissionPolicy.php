<?php

namespace App\Policies;

use App\Enums\PermissionType;
use App\Models\Employee;
use App\Models\Permission;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Employee $employee): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Employee $employee, Permission $Permission): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Employee $employee): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $employee, Permission $Permission): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Employee $employee, Permission $Permission): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Employee $employee, Permission $Permission): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Employee $employee, Permission $Permission): bool
    {
        return $employee->hasPermissionTo(PermissionType::RolePermission->value);
    }
}
