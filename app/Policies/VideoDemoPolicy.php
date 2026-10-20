<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\VideoDemo;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class VideoDemoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VideoDemo');
    }

    public function view(AuthUser $authUser, VideoDemo $videoDemo): bool
    {
        return $authUser->can('View:VideoDemo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VideoDemo');
    }

    public function update(AuthUser $authUser, VideoDemo $videoDemo): bool
    {
        return $authUser->can('Update:VideoDemo');
    }

    public function delete(AuthUser $authUser, VideoDemo $videoDemo): bool
    {
        return $authUser->can('Delete:VideoDemo');
    }
}
