<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\CertificatePolicy;
use App\Policies\CourseCategoryPolicy;
use App\Policies\CoursePolicy;
use App\Policies\CourseSectionPolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Course::class => CoursePolicy::class,
        CourseSection::class => CourseSectionPolicy::class,
        Category::class => CategoryPolicy::class,
        CourseCategory::class => CourseCategoryPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        Certificate::class => CertificatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define custom gates for dashboard access
        Gate::define('access_admin_dashboard', function (User $user) {
            return $user->can('access.admin.dashboard');
        });

        Gate::define('access_instructor_dashboard', function (User $user) {
            return $user->can('access.instructor.dashboard');
        });

        Gate::define('access_student_dashboard', function (User $user) {
            return $user->can('access.student.dashboard');
        });

        // Define gates for role-based access
        Gate::define('is_super_admin', function (User $user) {
            return $user->hasRole('super-admin');
        });

        Gate::define('is_admin', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('super-admin');
        });

        Gate::define('is_instructor', function (User $user) {
            return $user->hasRole('instructor');
        });

        Gate::define('is_student', function (User $user) {
            return $user->hasRole('student');
        });

        // Define gates for specific permission groups
        Gate::define('manage_users', function (User $user) {
            return $user->can('view.users') || $user->can('create.users') ||
                   $user->can('edit.users') || $user->can('delete.users');
        });

        Gate::define('manage_courses', function (User $user) {
            return $user->can('view.courses') || $user->can('create.courses') ||
                   $user->can('edit.courses') || $user->can('delete.courses') ||
                   $user->can('publish.courses') || $user->can('manage.own.courses');
        });

        Gate::define('manage_categories', function (User $user) {
            return $user->can('view.categories') || $user->can('create.categories') ||
                   $user->can('edit.categories') || $user->can('delete.categories');
        });

        Gate::define('manage_enrollments', function (User $user) {
            return $user->can('view.enrollments') || $user->can('create.enrollments') ||
                   $user->can('edit.enrollments') || $user->can('delete.enrollments') ||
                   $user->can('view.own.enrollments');
        });

        Gate::define('manage_certificates', function (User $user) {
            return $user->can('view.certificates') || $user->can('create.certificates') ||
                   $user->can('edit.certificates') || $user->can('delete.certificates') ||
                   $user->can('view.own.certificates');
        });

        // Define gates for system administration
        Gate::define('manage_system', function (User $user) {
            return $user->can('view.analytics') || $user->can('export.reports') ||
                   $user->can('manage.settings') || $user->can('view.logs') ||
                   $user->can('backup.system') || $user->can('clear.cache');
        });

        // Define gates for bulk operations
        Gate::define('bulk_operations', function (User $user) {
            return $user->can('bulk.delete.users') || $user->can('bulk.delete.courses') ||
                   $user->can('bulk.delete.categories');
        });

        // Define gates for export operations
        Gate::define('export_data', function (User $user) {
            return $user->can('export.users') || $user->can('export.courses') ||
                   $user->can('export.enrollments') || $user->can('export.certificates') ||
                   $user->can('export.reports');
        });
    }
}
