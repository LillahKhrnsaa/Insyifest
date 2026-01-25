<?php

namespace App\Policies;

use App\Models\FormRegistration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormRegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.form_registrations');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormRegistration $formRegistration): bool
    {
        return $user->can('view.form_registrations', $formRegistration);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.form_registrations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormRegistration $formRegistration): bool
    {
        return $user->can('update.form_registrations', $formRegistration);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormRegistration $formRegistration): bool
    {
        return $user->can('delete.form_registrations', $formRegistration);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormRegistration $formRegistration): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormRegistration $formRegistration): bool
    {
        return false;
    }
}
