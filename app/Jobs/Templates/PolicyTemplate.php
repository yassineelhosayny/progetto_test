<?php

return '<?php

declare(strict_types=1);

namespace App\\Policies;

use Illuminate\\Foundation\\Auth\\User as AuthUser;
use App\\Models\\{{MODEL_NAME}};
use Illuminate\\Auth\\Access\\HandlesAuthorization;

class {{POLICY_NAME}}
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can(\'ViewAny:{{RESOURCE_NAME}}\');
    }

    public function view(AuthUser $authUser, {{MODEL_NAME}} ${{MODEL_VARIABLE}}): bool
    {
        return $authUser->can(\'View:{{RESOURCE_NAME}}\');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can(\'Create:{{RESOURCE_NAME}}\');
    }

    public function update(AuthUser $authUser, {{MODEL_NAME}} ${{MODEL_VARIABLE}}): bool
    {
        return $authUser->can(\'Update:{{RESOURCE_NAME}}\');
    }

    public function delete(AuthUser $authUser, {{MODEL_NAME}} ${{MODEL_VARIABLE}}): bool
    {
        return $authUser->can(\'Delete:{{RESOURCE_NAME}}\');
    }

    public function restore(AuthUser $authUser, {{MODEL_NAME}} ${{MODEL_VARIABLE}}): bool
    {
        return $authUser->can(\'Restore:{{RESOURCE_NAME}}\');
    }

    public function forceDelete(AuthUser $authUser, {{MODEL_NAME}} ${{MODEL_VARIABLE}}): bool
    {
        return $authUser->can(\'ForceDelete:{{RESOURCE_NAME}}\');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can(\'ForceDeleteAny:{{RESOURCE_NAME}}\');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can(\'RestoreAny:{{RESOURCE_NAME}}\');
    }

    public function replicate(AuthUser $authUser, {{MODEL_NAME}} ${{MODEL_VARIABLE}}): bool
    {
        return $authUser->can(\'Replicate:{{RESOURCE_NAME}}\');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can(\'Reorder:{{RESOURCE_NAME}}\');
    }
}
';