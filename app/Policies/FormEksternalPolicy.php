<?php

namespace App\Policies;

use App\Models\FormEksternal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormEksternalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.form_eksternals');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormEksternal $formEksternal): bool
    {
        return $user->can('view.form_eksternals', $formEksternal);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.form_eksternals');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormEksternal $formEksternal): bool
    {
        return $user->can('update.form_eksternals', $formEksternal);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormEksternal $formEksternal): bool
    {
        return $user->can('delete.form_eksternals', $formEksternal);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormEksternal $formEksternal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormEksternal $formEksternal): bool
    {
        return false;
    }
}
