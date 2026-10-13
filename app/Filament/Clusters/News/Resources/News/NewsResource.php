<?php

namespace App\Filament\Clusters\News\Resources\News;

use App\Filament\Clusters\News\NewsCluster;
use App\Filament\Clusters\News\Resources\News\Pages\CreateNews;
use App\Filament\Clusters\News\Resources\News\Pages\EditNews;
use App\Filament\Clusters\News\Resources\News\Pages\ListNews;
use App\Filament\Clusters\News\Resources\News\Pages\ViewNews;
use App\Filament\Clusters\News\Resources\News\Schemas\NewsForm;
use App\Filament\Clusters\News\Resources\News\Schemas\NewsInfolist;
use App\Filament\Clusters\News\Resources\News\Tables\NewsTable;
use App\Models\News;
use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class NewsResource extends Resource
    
{

    protected static ?string $modelLabel = 'Articolo';

    protected static ?string $pluralModelLabel = 'Articoli';

    protected static ?string $cluster = NewsCluster::class;

    protected static ?int $navigationSort = 0;

    /**
     * Mostra la risorsa nel menu anche se l’utente ha permessi granulari (view news X)
     */
    public static function canAccessNavigation(): bool
    {
        $user = auth()->user();
        if (! $user) return false;

        // Ruolo admin
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
        // ...existing code...
            return true;
        }

        // Permesso globale (snake_case o StudlyCase:Resource)
        $perms = [
            'view_any_news',
            'view news',
            'ViewAny:News',
            'View:News',
        ];
        foreach ($perms as $perm) {
            if ($user->can($perm)) {
                // ...existing code...
                return true;
            }
        }

        // Permessi granulari: almeno uno (view o update)
        $hasGranular = $user->getAllPermissions()->pluck('name')->contains(fn($p)=> str_starts_with($p, 'view news '));
        // ...existing code...
        return $hasGranular;
    }
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $recordTitleAttribute = null;

    public static function form(Schema $schema): Schema
    {
        return NewsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNews::route('/'),
            'create' => CreateNews::route('/create'),
            'view' => ViewNews::route('/{record}'),
            'edit' => EditNews::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}
