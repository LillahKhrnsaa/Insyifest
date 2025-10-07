<?php

namespace App\Policies;

use App\Models\TrainingPackage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TrainingPackagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.training_packages');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TrainingPackage $trainingPackage): bool
    {
        return $user->can('view.training_packages', $trainingPackage);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.training_packages');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrainingPackage $trainingPackage): bool
    {
        return $user->can('update.training_packages', $trainingPackage);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TrainingPackage $trainingPackage): bool
    {
        return $user->can('delete.training_packages', $trainingPackage);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TrainingPackage $trainingPackage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TrainingPackage $trainingPackage): bool
    {
        return false;
    }
}
