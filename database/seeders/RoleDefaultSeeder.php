<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use App\Enums\RoleDefault;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleDefaultSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (!Permission::count()) {
            $this->command->info('Import permission first');
            return;
        }

        if (Role::count()) Role::truncate();

        foreach (RoleDefault::cases() as $role) {
            Role::create(['name' => $role->value]);
        }

        $role = Role::where('name', RoleDefault::Contributor->value)->first();
        $permissions = Permission::whereIn('name', [
            PermissionType::News->value,
            PermissionType::Gallery->value,
        ])->pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $employees = Employee::all();

        foreach ($employees as $employee) {
            $employee->assignRole([$role->id]);
        }

        $role = Role::where('name', RoleDefault::PublicationAdministrator->value)->first();
        $permissions = Permission::whereIn('name', [
            PermissionType::Page->value,
            PermissionType::Slideshow->value,
            PermissionType::ArticleCategory->value,
            PermissionType::Tag->value,
            PermissionType::Menu->value,
        ])->pluck('id','id')->all();
        $role->syncPermissions($permissions);

        $role = Role::where('name', RoleDefault::PublicInformationAdministrator->value)->first();
        $permissions = Permission::whereIn('name', [
            PermissionType::PublicInformation->value,
            PermissionType::PublicInformationCategory->value,
        ])->pluck('id','id')->all();
        $role->syncPermissions($permissions);

        $role = Role::where('name', RoleDefault::Roster->value)->first();
        $permissions = Permission::whereIn('name', [
            PermissionType::RolePermission->value,
        ])->pluck('id','id')->all();
        $role->syncPermissions($permissions);

        $role = Role::where('name', RoleDefault::EmployeeAffair->value)->first();
        $permissions = Permission::whereIn('name', [
            PermissionType::Employee->value,
        ])->pluck('id','id')->all();
        $role->syncPermissions($permissions);

    }
}