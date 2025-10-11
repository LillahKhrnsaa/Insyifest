<?php

namespace App\Policies;

use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewAny.payment_histories');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentHistory $paymentHistory): bool
    {
        return $user->can('view.payment_histories', $paymentHistory);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create.payment_histories');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentHistory $paymentHistory): bool
    {
        return $user->can('update.payment_histories', $paymentHistory);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentHistory $paymentHistory): bool
    {
        return $user->can('delete.payment_histories', $paymentHistory);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentHistory $paymentHistory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentHistory $paymentHistory): bool
    {
        return false;
    }
}
