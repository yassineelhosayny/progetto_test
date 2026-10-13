<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Pages;

use App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\{{RESOURCE_NAME}}Resource;
use Filament\\Actions;
use Filament\\Resources\\Pages\\ListRecords;

class List{{RESOURCE_NAME}} extends ListRecords
{
    protected static string $resource = {{RESOURCE_NAME}}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        $query = parent::getTableQuery();
        $user = auth()->user();
        if (!$user) return $query->whereKey([]);

        // Se admin o super_admin, mostra tutto
        if ($user->hasRole(\'admin\') || $user->hasRole(\'super_admin\')) {
            return $query;
        }

        // Se permesso globale, mostra tutto
        if ($user->can(\'view_any_\' . strtolower(\'{{RESOURCE_NAME}}\')) || $user->can(\'view\' . strtolower(\'{{RESOURCE_NAME}}\')) || $user->can(\'ViewAny:{{RESOURCE_NAME}}\') || $user->can(\'View:{{RESOURCE_NAME}}\')) {
            return $query;
        }

        // Permessi granulari: mostra solo le {{RESOURCE_NAME}} per cui ha permesso view {{RESOURCE_NAME}} X
        $ids = $user->getAllPermissions()->pluck(\'name\')
            ->filter(fn($p) => str_starts_with($p, \'view \' . strtolower(\'{{RESOURCE_NAME}}\') . \' \'))
            ->map(fn($p) => (int) str_replace(\'view \' . strtolower(\'{{RESOURCE_NAME}}\') . \' \', \'\', $p))
            ->filter();
        return $query->whereKey($ids);
    }
}
';