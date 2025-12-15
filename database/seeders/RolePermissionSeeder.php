<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
       
        $userManagementModule = Module::create(['name' => 'User Management']);
        $roleManagementModule = Module::create(['name' => 'Role Management']);
        $moduleManagementModule = Module::create(['name' => 'Module Management']);
        $permissionManagementModule = Module::create(['name' => 'Permission Management']);
        $dashboardModule = Module::create(['name' => 'Dashboard']);

        // Create Permissions
        $permissions = [
            // Dashboard
            ['name' => 'Access Dashboard', 'module_id' => $dashboardModule->id],

            // User Management
            ['name' => 'Access User', 'module_id' => $userManagementModule->id],
            ['name' => 'Create Users', 'module_id' => $userManagementModule->id],
            ['name' => 'Edit User', 'module_id' => $userManagementModule->id],
            ['name' => 'Delete User', 'module_id' => $userManagementModule->id],

            // Role Management
            ['name' => 'Access Role', 'module_id' => $roleManagementModule->id],
            ['name' => 'Create Role', 'module_id' => $roleManagementModule->id],
            ['name' => 'Edit Role', 'module_id' => $roleManagementModule->id],
            ['name' => 'Delete Role', 'module_id' => $roleManagementModule->id],
            ['name' => 'Assigned Permission', 'module_id' => $roleManagementModule->id],

            // Module Management
            ['name' => 'Access Module', 'module_id' => $moduleManagementModule->id],
            ['name' => 'Create Module', 'module_id' => $moduleManagementModule->id],
            ['name' => 'Edit Module', 'module_id' => $moduleManagementModule->id],
            ['name' => 'Delete Module', 'module_id' => $moduleManagementModule->id],

            // Permission Management
            ['name' => 'Access Permission', 'module_id' => $permissionManagementModule->id],
            ['name' => 'Create Permission', 'module_id' => $permissionManagementModule->id],
            ['name' => 'Edit Permission', 'module_id' => $permissionManagementModule->id],
            ['name' => 'Delete Permission', 'module_id' => $permissionManagementModule->id],
        ];

        // Insert permissions
        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        $adminRole = Role::where('name', 'SuperAdmin')->first();
       
        // Assign permissions to roles (assuming you already have roles created)
        $adminRole->permissions()->attach(Permission::all()); // Admin has all permissions
        
    }
}