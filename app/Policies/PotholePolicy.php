<?php

namespace App\Policies;

use App\Enums\Roles;
use App\Models\Pothole;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PotholePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole([Roles::ADMIN->value, Roles::INSTITUTION->value])) {
            return true;
        }

        return $user->hasRole(Roles::USER->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pothole $pothole): bool
    {
        if ($user->hasRole([Roles::ADMIN->value, Roles::INSTITUTION->value])) {
            return true;
        }

        return $user->id === $pothole->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole([Roles::ADMIN->value, Roles::USER->value])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pothole $pothole): bool
    {
        return $user->hasRole([Roles::ADMIN->value, Roles::INSTITUTION->value]) || $user->id === $pothole->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pothole $pothole): bool
    {
        return $user->hasRole([Roles::ADMIN->value, Roles::INSTITUTION->value]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pothole $pothole): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pothole $pothole): bool
    {
        //
    }
}
