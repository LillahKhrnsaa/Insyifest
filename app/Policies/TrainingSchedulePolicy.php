<?php

namespace App\Policies;

use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TrainingSchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.training_schedules');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TrainingSchedule $trainingSchedule): bool
    {
        return $user->can('view.training_schedules', $trainingSchedule);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.training_schedules');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrainingSchedule $trainingSchedule): bool
    {
        return $user->can('update.training_schedules', $trainingSchedule);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TrainingSchedule $trainingSchedule): bool
    {
        return $user->can('delete.training_schedules', $trainingSchedule);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TrainingSchedule $trainingSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TrainingSchedule $trainingSchedule): bool
    {
        return false;
    }
}
