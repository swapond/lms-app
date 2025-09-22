<?php

namespace App\Policies;

use App\Models\CourseSection;
use App\Models\User;

class CourseSectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view.course.sections');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CourseSection $courseSection): bool
    {
        return $user->can('view.course.sections');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.course.sections');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CourseSection $courseSection): bool
    {
        // Super admin and admin can edit any course section
        if ($user->can('edit.course.sections')) {
            return true;
        }

        // Instructors can only edit sections of their own courses
        if ($user->can('manage_own.course.sections')) {
            return $courseSection->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CourseSection $courseSection): bool
    {
        // Super admin and admin can delete any course section
        if ($user->can('delete.course.sections')) {
            return true;
        }

        // Instructors can only delete sections of their own courses
        if ($user->can('manage_own.course.sections')) {
            return $courseSection->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can manage their own course sections.
     */
    public function manageOwn(User $user, CourseSection $courseSection): bool
    {
        return $user->can('manage_own.course.sections') && $courseSection->course->instructor_id === $user->id;
    }
}
