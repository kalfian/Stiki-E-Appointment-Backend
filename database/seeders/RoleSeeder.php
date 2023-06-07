<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        // Superadmin
        Permission::create(['name' => 'admin_superadmin_create']);
        Permission::create(['name' => 'admin_superadmin_read']);
        Permission::create(['name' => 'admin_superadmin_update']);
        Permission::create(['name' => 'admin_superadmin_delete']);

        // Admin
        Permission::create(['name' => 'admin_admin_create']);
        Permission::create(['name' => 'admin_admin_read']);
        Permission::create(['name' => 'admin_admin_update']);
        Permission::create(['name' => 'admin_admin_delete']);

        // Lecture
        $lecture_create = Permission::create(['name' => 'admin_lecture_create']);
        $lecture_read = Permission::create(['name' => 'admin_lecture_read']);
        $lecture_update = Permission::create(['name' => 'admin_lecture_update']);
        $lecture_delete = Permission::create(['name' => 'admin_lecture_delete']);

        // Student
        $student_create = Permission::create(['name' => 'admin_student_create']);
        $student_read = Permission::create(['name' => 'admin_student_read']);
        $student_update = Permission::create(['name' => 'admin_student_update']);
        $student_delete = Permission::create(['name' => 'admin_student_delete']);

        // Activity
        $activity_create = Permission::create(['name' => 'admin_activity_create']);
        $activity_read = Permission::create(['name' => 'admin_activity_read']);
        $activity_update = Permission::create(['name' => 'admin_activity_update']);
        $activity_delete = Permission::create(['name' => 'admin_activity_delete']);

        // Logbook
        $logbook_create = Permission::create(['name' => 'admin_logbook_create']);
        $logbook_read = Permission::create(['name' => 'admin_logbook_read']);
        $logbook_update = Permission::create(['name' => 'admin_logbook_update']);
        $logbook_delete = Permission::create(['name' => 'admin_logbook_delete']);

        // Appointment
        $appointment_create = Permission::create(['name' => 'admin_appointment_create']);
        $appointment_read = Permission::create(['name' => 'admin_appointment_read']);
        $appointment_update = Permission::create(['name' => 'admin_appointment_update']);
        $appointment_delete = Permission::create(['name' => 'admin_appointment_delete']);

        $role_superadmin = Role::create(['name' => role()::ROLE_SUPERADMIN]);
        $role_superadmin->givePermissionTo(Permission::all());

        $role_admin = Role::create(['name' => role()::ROLE_ADMIN]);
        $role_admin->givePermissionTo([
            $lecture_create->id,
            $lecture_read->id,
            $lecture_update->id,
            $lecture_delete->id,
            $student_create->id,
            $student_read->id,
            $student_update->id,
            $student_delete->id,
            $activity_create->id,
            $activity_read->id,
            $activity_update->id,
            $activity_delete->id,
            $logbook_create->id,
            $logbook_read->id,
            $logbook_update->id,
            $logbook_delete->id,
            $appointment_create->id,
            $appointment_read->id,
            $appointment_update->id,
            $appointment_delete->id
        ]);

        Role::create(['name' => role()::ROLE_STUDENT]);
        Role::create(['name' => role()::ROLE_LECTURE]);
    }
}
