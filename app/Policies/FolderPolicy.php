<?php

namespace App\Policies;

use App\Enums\PermissionType;
use App\Models\Employee;
use App\Models\Folder;
use Illuminate\Auth\Access\Response;

class FolderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Employee $employee): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Employee $employee, Folder $folder): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Employee $employee): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $employee, Folder $folder): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Employee $employee, Folder $folder): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Employee $employee, Folder $folder): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Employee $employee, Folder $folder): bool
    {
        return $employee->hasPermissionTo(PermissionType::Archives->value);
    }
}
