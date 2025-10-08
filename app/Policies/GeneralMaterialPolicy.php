<?php

namespace App\Policies;

use App\Models\GeneralMaterial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GeneralMaterialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.general_materials');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeneralMaterial $generalMaterial): bool
    {
        return $user->can('view.general_materials', $generalMaterial);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.general_materials');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeneralMaterial $generalMaterial): bool
    {
        return $user->can('update.general_materials', $generalMaterial);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeneralMaterial $generalMaterial): bool
    {
        return $user->can('delete.general_materials', $generalMaterial);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeneralMaterial $generalMaterial): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeneralMaterial $generalMaterial): bool
    {
        return false;
    }
}
