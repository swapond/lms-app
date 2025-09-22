<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view.courses');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        return $user->can('view.courses');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.courses');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        // Super admin and admin can edit any course
        if ($user->can('edit.courses')) {
            return true;
        }

        // Instructors can only edit their own courses
        if ($user->can('manage_own.courses')) {
            return $course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        // Super admin and admin can delete any course
        if ($user->can('delete.courses')) {
            return true;
        }

        // Instructors can only delete their own courses if they have manage_own permission
        if ($user->can('manage_own.courses')) {
            return $course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        // Super admin and admin can restore any course
        if ($user->can('restore.courses')) {
            return true;
        }

        // Instructors can only restore their own courses
        if ($user->can('manage.own.courses')) {
            return $course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->can('force.delete.courses');
    }

    /**
     * Determine whether the user can publish the course.
     */
    public function publish(User $user, Course $course): bool
    {
        // Only admin and super admin can publish courses
        if ($user->can('publish.courses')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can manage their own course.
     */
    public function manageOwn(User $user, Course $course): bool
    {
        return $user->can('manage.own.courses') && $course->instructor_id === $user->id;
    }

    /**
     * Determine whether the user can unpublish the course.
     */
    public function unpublish(User $user, Course $course): bool
    {
        if ($user->can('unpublish.courses')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can feature the course.
     */
    public function feature(User $user, Course $course): bool
    {
        return $user->can('feature.courses');
    }

    /**
     * Determine whether the user can unfeature the course.
     */
    public function unfeature(User $user, Course $course): bool
    {
        return $user->can('unfeature.courses');
    }

    /**
     * Determine whether the user can bulk delete courses.
     */
    public function bulkDelete(User $user): bool
    {
        return $user->can('bulk.delete.courses');
    }

    /**
     * Determine whether the user can export courses.
     */
    public function export(User $user): bool
    {
        return $user->can('export.courses');
    }
}
