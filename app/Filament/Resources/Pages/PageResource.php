<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\Pages\ViewPage;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Schemas\PageInfolist;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class PageResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Contenuti';

    protected static ?int $navigationSort = 2;
    
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'Pagine';

    protected static ?string $navigationLabel = 'Pagine';

    protected static ?string $modelLabel = 'Pagina';

    protected static ?string $pluralModelLabel = 'Pagine';

    /**
     * Mostra la risorsa nel menu anche se l’utente ha permessi granulari (view page X)
     */
    public static function canAccessNavigation(): bool
    {
        $user = auth()->user();
        // ...existing code...
        if (! $user) return false;

        // Ruolo admin
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            // ...existing code...
            return true;
        }

        // Permesso globale (snake_case o StudlyCase:Resource)
        $perms = [
            'view_any_page',
            'view page',
            'ViewAny:Page',
            'View:Page',
        ];
        foreach ($perms as $perm) {
            if ($user->can($perm)) {
                // ...existing code...
                return true;
            }
        }

        // Permessi granulari: almeno uno
        $hasGranular = $user->getAllPermissions()->pluck('name')->contains(fn($p) => str_starts_with($p, 'view page '));
    // ...existing code...
        return $hasGranular;
    }

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    // Ritorna il titolo del record (traduzione italiana con fallback)
    public static function getRecordTitle(?Model $record): Htmlable|string|null
    {
        if (! $record) {
            return null;
        }

        if (method_exists($record, 'getTranslation')) {
            $title = $record->getTranslation('title', 'it', false);
            if (! empty($title)) {
                return $title;
            }

            $all = $record->getTranslations('title');
            if (is_array($all) && count($all) > 0) {
                return reset($all);
            }
        }

        $raw = $record->getAttributes()['title'] ?? null;
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded['it'] ?? reset($decoded) ?? $raw;
            }
            return $raw;
        }

        return (string) ($record->title ?? '');
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'view' => ViewPage::route('/{record}'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
