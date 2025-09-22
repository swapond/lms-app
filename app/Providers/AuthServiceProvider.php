<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\CourseCategory;
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
        Gate::define('access-admin-dashboard', function (User $user) {
            return $user->can('access.admin.dashboard');
        });

        Gate::define('access-instructor-dashboard', function (User $user) {
            return $user->can('access.instructor.dashboard');
        });

        Gate::define('access-student-dashboard', function (User $user) {
            return $user->can('access.student.dashboard');
        });

        // Define gates for role-based access
        Gate::define('is-super-admin', function (User $user) {
            return $user->hasRole('super-admin');
        });

        Gate::define('is-admin', function (User $user) {
            return $user->hasRole('admin') || $user->hasRole('super-admin');
        });

        Gate::define('is-instructor', function (User $user) {
            return $user->hasRole('instructor');
        });

        Gate::define('is-student', function (User $user) {
            return $user->hasRole('student');
        });

        // Define gates for specific permission groups
        Gate::define('manage-users', function (User $user) {
            return $user->can('view.users') || $user->can('create.users') ||
                   $user->can('edit.users') || $user->can('delete.users');
        });

        Gate::define('manage-courses', function (User $user) {
            return $user->can('view.courses') || $user->can('create.courses') ||
                   $user->can('edit.courses') || $user->can('delete.courses') ||
                   $user->can('publish.courses') || $user->can('manage.own.courses');
        });

        Gate::define('manage-categories', function (User $user) {
            return $user->can('view.categories') || $user->can('create.categories') ||
                   $user->can('edit.categories') || $user->can('delete.categories');
        });

        Gate::define('manage-enrollments', function (User $user) {
            return $user->can('view.enrollments') || $user->can('create.enrollments') ||
                   $user->can('edit.enrollments') || $user->can('delete.enrollments') ||
                   $user->can('view.own.enrollments');
        });

        Gate::define('manage-certificates', function (User $user) {
            return $user->can('view.certificates') || $user->can('create.certificates') ||
                   $user->can('edit.certificates') || $user->can('delete.certificates') ||
                   $user->can('view.own.certificates');
        });

        // Define gates for system administration
        Gate::define('manage-system', function (User $user) {
            return $user->can('view.analytics') || $user->can('export.reports') ||
                   $user->can('manage.settings') || $user->can('view.logs') ||
                   $user->can('backup.system') || $user->can('clear.cache');
        });

        // Define gates for bulk operations
        Gate::define('bulk-operations', function (User $user) {
            return $user->can('bulk.delete.users') || $user->can('bulk.delete.courses') ||
                   $user->can('bulk.delete.categories') || $user->can('bulk.delete.enrollments');
        });

        // Define gates for export operations
        Gate::define('export-data', function (User $user) {
            return $user->can('export.users') || $user->can('export.courses') ||
                   $user->can('export.enrollments') || $user->can('export.certificates') ||
                   $user->can('export.reports');
        });
    }
}
