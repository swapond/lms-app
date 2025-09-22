<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view.certificates') || $user->can('view_own.certificates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Certificate $certificate): bool
    {
        // Super admin and admin can view any certificate
        if ($user->can('view.certificates')) {
            return true;
        }

        // Students can view their own certificates
        if ($user->can('view_own.certificates')) {
            return $certificate->user_id === $user->id;
        }

        // Instructors can view certificates for their courses
        if ($user->hasRole('instructor')) {
            return $certificate->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.certificates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Certificate $certificate): bool
    {
        // Super admin and admin can edit any certificate
        if ($user->can('edit.certificates')) {
            return true;
        }

        // Instructors can edit certificates for their courses
        if ($user->hasRole('instructor')) {
            return $certificate->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Certificate $certificate): bool
    {
        // Super admin and admin can delete any certificate
        if ($user->can('delete.certificates')) {
            return true;
        }

        // Instructors can delete certificates for their courses
        if ($user->hasRole('instructor')) {
            return $certificate->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Certificate $certificate): bool
    {
        return $this->delete($user, $certificate);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Certificate $certificate): bool
    {
        return $user->can('delete.certificates');
    }

    /**
     * Determine whether the user can view their own certificates.
     */
    public function viewOwn(User $user, Certificate $certificate): bool
    {
        return $user->can('view_own.certificates') && $certificate->user_id === $user->id;
    }
}
