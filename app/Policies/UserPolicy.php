<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:User');
    }

    public function view(AuthUser $authUser, User $user): bool
    {
        return $authUser->can('View:User');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasRole('super_admin');
    }

    public function update(AuthUser $authUser, User $user): bool
    {
        if ($user->hasRole('super_admin') && ! $authUser->hasRole('super_admin')) {
            return false;
        }

        return $authUser->can('Update:User');
    }

    public function delete(AuthUser $authUser, User $user): bool
    {
        if ($user->hasRole('super_admin') && ! $authUser->hasRole('super_admin')) {
            return false;
        }

        return $authUser->can('Delete:User');
    }
}
