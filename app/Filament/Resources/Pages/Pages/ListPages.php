<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        $query = parent::getTableQuery();
        $user = auth()->user();
    if (!$user) return $query->whereKey([]);

        // Se admin o super_admin, mostra tutto
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return $query;
        }

        // Se permesso globale, mostra tutto
        if ($user->can('view_any_page') || $user->can('view page') || $user->can('ViewAny:Page') || $user->can('View:Page')) {
            return $query;
        }

        // Permessi granulari: mostra solo le pagine per cui ha permesso view page X
        $ids = $user->getAllPermissions()->pluck('name')
            ->filter(fn($p) => str_starts_with($p, 'view page '))
            ->map(fn($p) => (int) str_replace('view page ', '', $p))
            ->filter();
        return $query->whereKey($ids);
    }
}
