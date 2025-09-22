<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    /**
     * Determine whether the user can access the admin dashboard.
     */
    public function accessAdmin(User $user): bool
    {
        return $user->can('access.admin.dashboard');
    }

    /**
     * Determine whether the user can access the instructor dashboard.
     */
    public function accessInstructor(User $user): bool
    {
        return $user->can('access.instructor.dashboard');
    }

    /**
     * Determine whether the user can access the student dashboard.
     */
    public function accessStudent(User $user): bool
    {
        return $user->can('access.student.dashboard');
    }

    /**
     * Determine the appropriate dashboard for the user.
     */
    public function getAppropriateRedirect(User $user): string
    {
        if ($user->can('access.admin.dashboard')) {
            return '/admin/dashboard';
        }

        if ($user->can('access.instructor.dashboard')) {
            return '/instructor/dashboard';
        }

        if ($user->can('access.student.dashboard')) {
            return '/student/dashboard';
        }

        // Default fallback
        return '/dashboard';
    }
}
