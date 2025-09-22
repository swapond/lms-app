<?php

namespace App\Policies;

use App\Models\CourseCategory;
use App\Models\User;

class CourseCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view.course.categories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CourseCategory $courseCategory): bool
    {
        return $user->can('view.course.categories');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.course.categories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CourseCategory $courseCategory): bool
    {
        // Super admin and admin can edit any course category
        if ($user->can('edit.course.categories')) {
            return true;
        }

        // Instructors can only edit categories for their own courses
        if ($user->can('manage.own.courses')) {
            return $courseCategory->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CourseCategory $courseCategory): bool
    {
        // Super admin and admin can delete any course category
        if ($user->can('delete.course.categories')) {
            return true;
        }

        // Instructors can only delete categories for their own courses
        if ($user->can('manage.own.courses')) {
            return $courseCategory->course->instructor_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CourseCategory $courseCategory): bool
    {
        return $this->delete($user, $courseCategory);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CourseCategory $courseCategory): bool
    {
        return $user->can('delete.course.categories');
    }

    /**
     * Determine whether the user can set as primary category.
     */
    public function setPrimary(User $user, CourseCategory $courseCategory): bool
    {
        return $this->update($user, $courseCategory);
    }

    /**
     * Determine whether the user can reorder categories.
     */
    public function reorder(User $user, CourseCategory $courseCategory): bool
    {
        return $this->update($user, $courseCategory);
    }
}
