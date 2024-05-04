<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['guard_name' => 'web', 'name' => 'shed-management-view']);
        Permission::create(['guard_name' => 'web', 'name' => 'shed-management-edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'shed-management-delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'farmer-management-view']);
        Permission::create(['guard_name' => 'web', 'name' => 'farmer-management-edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'farmer-management-delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'user-management']);
        Permission::create(['guard_name' => 'web', 'name' => 'user-management-view']);
        Permission::create(['guard_name' => 'web', 'name' => 'user-management-edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'user-management-delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'vehicle-management-view']);
        Permission::create(['guard_name' => 'web', 'name' => 'vehicle-management-edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'vehicle-management-delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'waste-management-view']);
        Permission::create(['guard_name' => 'web', 'name' => 'waste-management-edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'waste-management-delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'weighment-management-view']);
        Permission::create(['guard_name' => 'web', 'name' => 'weighment-management-edit']);
        Permission::create(['guard_name' => 'web', 'name' => 'weighment-management-delete']);
        Permission::create(['guard_name' => 'web', 'name' => 'dashboard-management']);
        Permission::create(['guard_name' => 'web', 'name' => 'shed-abstract-report']);
        Permission::create(['guard_name' => 'web', 'name' => 'shed-detail-report']);
        Permission::create(['guard_name' => 'web', 'name' => 'slip-report']);
        

        $superAdminRole = Role::create(['guard_name' => 'web', 'name' => 'Super Admin']);
        $adminRole = Role::create(['guard_name' => 'web', 'name' => 'Admin']);
        $supervisorRole = Role::create(['guard_name' => 'web', 'name' => 'Shed Supervisor']);

        $superAdminRole->givePermissionTo([
            'shed-management-view',
            'shed-management-edit',
            'shed-management-delete',
            'farmer-management-view',
            'farmer-management-edit',
            'farmer-management-delete',
            'user-management',
            'user-management-view',
            'user-management-edit',
            'user-management-delete',
            'vehicle-management-view',
            'vehicle-management-edit',
            'vehicle-management-delete',
            'waste-management-view',
            'waste-management-edit',
            'waste-management-delete',
            'weighment-management-view',
            'weighment-management-edit',
            'weighment-management-delete',
            'dashboard-management',
            'shed-abstract-report',
            'shed-detail-report',
            'slip-report'
        ]);
    }
}
