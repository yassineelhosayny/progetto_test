<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\VideoSource;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class VideoSourcePolicy
{
    use HandlesAuthorization;

    // Le versioni non hanno permessi separati: ereditano i permessi Video.
    // Chi può vedere la lista dei video può vedere anche le loro versioni.
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Video');
    }

    // Controlla la visualizzazione di una singola versione.
    public function view(AuthUser $authUser, VideoSource $videoSource): bool
    {
        return $authUser->can('View:Video');
    }

    // Per creare una versione bisogna poter modificare il video proprietario.
    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Update:Video');
    }

    // Anche la modifica di una versione usa il permesso Update:Video.
    public function update(AuthUser $authUser, VideoSource $videoSource): bool
    {
        return $authUser->can('Update:Video');
    }

    // Anche l'eliminazione usa Update:Video; il Relation Manager impedisce
    // inoltre di eliminare la versione attualmente corrente.
    public function delete(AuthUser $authUser, VideoSource $videoSource): bool
    {
        return $authUser->can('Update:Video');
    }
}
