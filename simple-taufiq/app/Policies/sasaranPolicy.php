<?php

namespace App\Policies;

use App\Models\User;
use App\Models\sasaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class sasaranPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_sasaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, sasaran $sasaran): bool
    {
        return $user->can('view_sasaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_sasaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, sasaran $sasaran): bool
    {
        return $user->can('update_sasaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, sasaran $sasaran): bool
    {
        return $user->can('delete_sasaran');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_sasaran');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, sasaran $sasaran): bool
    {
        return $user->can('force_delete_sasaran');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_sasaran');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, sasaran $sasaran): bool
    {
        return $user->can('restore_sasaran');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_sasaran');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, sasaran $sasaran): bool
    {
        return $user->can('replicate_sasaran');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_sasaran');
    }
}
