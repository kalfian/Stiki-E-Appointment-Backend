<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Role Seeder
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Role::create(['name' => role()::ROLE_SUPERADMIN]);
        Role::create(['name' => role()::ROLE_ADMIN]);
        Role::create(['name' => role()::ROLE_STUDENT]);
        Role::create(['name' => role()::ROLE_LECTURE]);
    }
}
