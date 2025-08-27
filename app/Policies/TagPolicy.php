<?php

namespace App\Policies;

use App\Enums\PermissionType;
use App\Models\Tag;
use App\Models\Employee;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Employee $employee): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Employee $employee, Tag $tag): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Employee $employee): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $employee, Tag $tag): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Employee $employee, Tag $tag): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Employee $employee, Tag $tag): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Employee $employee, Tag $tag): bool
    {
        return $employee->hasPermissionTo(PermissionType::Tag->value);
    }
}
