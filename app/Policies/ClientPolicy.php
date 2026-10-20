<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Client;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ClientPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Client');
    }

    public function view(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('View:Client');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Client');
    }

    public function update(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('Update:Client');
    }

    public function delete(AuthUser $authUser, Client $client): bool
    {
        return $authUser->can('Delete:Client');
    }
}
