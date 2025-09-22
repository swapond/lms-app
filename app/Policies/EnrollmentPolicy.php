<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view.enrollments') || $user->can('view_own.enrollments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        // Super admin and admin can view any enrollment
        if ($user->can('view.enrollments')) {
            return true;
        }

        // Students can view their own enrollments
        if ($user->can('view_own.enrollments')) {
            return $enrollment->user_id === $user->id;
        }

        // Instructors can view enrollments for their courses
        if ($user->hasRole('instructor')) {
            return $enrollment->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.enrollments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        // Super admin and admin can edit any enrollment
        if ($user->can('edit.enrollments')) {
            return true;
        }

        // Instructors can edit enrollments for their courses
        if ($user->hasRole('instructor')) {
            return $enrollment->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        // Super admin and admin can delete any enrollment
        if ($user->can('delete.enrollments')) {
            return true;
        }

        // Instructors can delete enrollments for their courses
        if ($user->hasRole('instructor')) {
            return $enrollment->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return $this->delete($user, $enrollment);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return $user->can('delete.enrollments');
    }

    /**
     * Determine whether the user can view their own enrollments.
     */
    public function viewOwn(User $user, Enrollment $enrollment): bool
    {
        return $user->can('view_own.enrollments') && $enrollment->user_id === $user->id;
    }
}
