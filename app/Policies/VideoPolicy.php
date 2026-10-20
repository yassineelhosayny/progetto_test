<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class VideoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Video');
    }

    public function view(AuthUser $authUser, Video $video): bool
    {
        return $authUser->can('View:Video');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Video');
    }

    public function update(AuthUser $authUser, Video $video): bool
    {
        return $authUser->can('Update:Video');
    }

    public function delete(AuthUser $authUser, Video $video): bool
    {
        return $authUser->can('Delete:Video');
    }
}
