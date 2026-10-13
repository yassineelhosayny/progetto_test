<?php

namespace App\Filament\Clusters\News\Resources\News\Pages;

use App\Filament\Clusters\News\Resources\News\NewsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

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
        if ($user->can('view_any_news') || $user->can('view news') || $user->can('ViewAny:News') || $user->can('View:News')) {
            return $query;
        }

        // Permessi granulari: mostra solo le news per cui ha permesso view news X
        $ids = $user->getAllPermissions()->pluck('name')
            ->filter(fn($p) => str_starts_with($p, 'view news '))
            ->map(fn($p) => (int) str_replace('view news ', '', $p))
            ->filter();
        return $query->whereKey($ids);
    }
}
