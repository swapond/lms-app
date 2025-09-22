<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        $student = Role::firstOrCreate(['name' => 'student']);

        $permissions = [
            // user managements
            'view.users',
            'create.users',
            'edit.users',
            'delete.users',
            'restore.users',
            'force.delete.users',
            'bulk.delete.users',
            'export.users',

            // course managements
            'view.courses',
            'create.courses',
            'edit.courses',
            'delete.courses',
            'restore.courses',
            'force.delete.courses',
            'publish.courses',
            'unpublish.courses',
            'feature.courses',
            'unfeature.courses',
            'manage.own.courses',
            'bulk.delete.courses',
            'export.courses',

            // course section managements
            'view.course.sections',
            'create.course.sections',
            'edit.course.sections',
            'delete.course.sections',
            'publish.course.sections',
            'unpublish.course.sections',
            'manage.own.course.sections',

            // category managements
            'view.categories',
            'create.categories',
            'edit.categories',
            'delete.categories',
            'restore.categories',
            'force.delete.categories',
            'bulk.delete.categories',

            // course category managements
            'view.course.categories',
            'create.course.categories',
            'edit.course.categories',
            'delete.course.categories',

            // enrollment managements
            'view.enrollments',
            'create.enrollments',
            'edit.enrollments',
            'delete.enrollments',
            'view.own.enrollments',
            'export.enrollments',

            // certificate managements
            'view.certificates',
            'create.certificates',
            'edit.certificates',
            'delete.certificates',
            'view.own.certificates',
            'export.certificates',

            // system managements
            'view.analytics',
            'export.reports',
            'manage.settings',
            'view.logs',
            'backup.system',
            'clear.cache',

            // dashboard managements
            'access.admin.dashboard',
            'access.instructor.dashboard',
            'access.student.dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'view.users',
            'create.users',
            'edit.users',
            'delete.users',
            'restore.users',
            'bulk.delete.users',
            'export.users',
            'view.courses',
            'create.courses',
            'edit.courses',
            'delete.courses',
            'restore.courses',
            'publish.courses',
            'unpublish.courses',
            'feature.courses',
            'unfeature.courses',
            'bulk.delete.courses',
            'export.courses',
            'view.categories',
            'create.categories',
            'edit.categories',
            'delete.categories',
            'restore.categories',
            'bulk.delete.categories',
            'view.course.categories',
            'create.course.categories',
            'edit.course.categories',
            'delete.course.categories',
            'view.enrollments',
            'create.enrollments',
            'edit.enrollments',
            'delete.enrollments',
            'export.enrollments',
            'view.certificates',
            'create.certificates',
            'edit.certificates',
            'delete.certificates',
            'export.certificates',
            'view.course.sections',
            'create.course.sections',
            'edit.course.sections',
            'delete.course.sections',
            'publish.course.sections',
            'unpublish.course.sections',
            'view.analytics',
            'export.reports',
            'manage.settings',
            'view.logs',
            'clear.cache',
            'access.admin.dashboard',
        ]);

        $instructor->givePermissionTo([
            'view.courses',
            'create.courses',
            'edit.courses',
            'manage.own.courses',
            'publish.course.sections',
            'unpublish.course.sections',
            'view.course.sections',
            'create.course.sections',
            'edit.course.sections',
            'delete.course.sections',
            'manage.own.course.sections',
            'view.course.categories',
            'create.course.categories',
            'edit.course.categories',
            'delete.course.categories',
            'view.enrollments',
            'edit.enrollments',
            'export.enrollments',
            'create.certificates',
            'view.certificates',
            'edit.certificates',
            'export.certificates',
            'access.instructor.dashboard',
        ]);

        $student->givePermissionTo([
            'view.courses',
            'view.course.sections',
            'create.enrollments',
            'view.own.enrollments',
            'view.own.certificates',
            'access.student.dashboard',
        ]);

        $superAdminUser = User::firstOrCreate(['email' => 'superadmin@example.com'], [
            'name' => 'Super Admin User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superAdminUser->assignRole('super-admin');

        $adminUser = User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('admin');

        $instructorUser = User::firstOrCreate(['email' => 'instructor@example.com'], [
            'name' => 'Instructor User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $instructorUser->assignRole('instructor');

        $studentUser = User::firstOrCreate(['email' => 'student@example.com'], [
            'name' => 'Student User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $studentUser->assignRole('student');

    }
}
