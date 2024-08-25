<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
//        // Roles
//        Permission::create(['name' => 'view roles']);
//        Permission::create(['name' => 'create roles']);
//        Permission::create(['name' => 'edit roles']);
//        Permission::create(['name' => 'delete roles']);
//
//        // Departments
//        Permission::create(['name' => 'view departments']);
//        Permission::create(['name' => 'create departments']);
//        Permission::create(['name' => 'edit departments']);
//        Permission::create(['name' => 'delete departments']);
//
//        // Activity Log
//        Permission::create(['name' => 'view activity logs']);
//
//        // Users
//        Permission::create(['name' => 'view users']);
//        Permission::create(['name' => 'create users']);
//        Permission::create(['name' => 'edit users']);
//        Permission::create(['name' => 'delete users']);
//
//        // Banks
//        Permission::create(['name' => 'view banks']);
//        Permission::create(['name' => 'create banks']);
//        Permission::create(['name' => 'edit banks']);
//        Permission::create(['name' => 'delete banks']);

        // Attendance
        Permission::create(['name' => 'view attendances']);
        Permission::create(['name' => 'create attendances']);
        Permission::create(['name' => 'edit attendances']);
        Permission::create(['name' => 'delete attendances']);
    }
}
