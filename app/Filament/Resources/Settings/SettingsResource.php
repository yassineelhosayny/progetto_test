<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\EditSettings;
use App\Filament\Resources\Settings\Pages\ListSettings;
use App\Filament\Resources\Settings\Schemas\SettingsForm;
use App\Filament\Resources\Settings\Tables\SettingsTable;
use App\Models\Settings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SettingsResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Amministrazione';

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Settings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $recordTitleAttribute = 'Impostazioni';

    protected static ?string $navigationLabel = 'Impostazioni';

    protected static ?string $modelLabel = 'Impostazioni';

    protected static ?string $pluralModelLabel = 'Impostazioni';

    public static function form(Schema $schema): Schema
    {
        return SettingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SettingsTable::configure($table);
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
            'index' => EditSettings::route('/'),
        ];
    }

    // shouldRegisterNavigation rimosso: la visibilità è ora gestita solo da canAccessNavigation

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getIndexUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return static::getUrl('index', $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters);
    }

    public static function getNavigationUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        return static::getIndexUrl($parameters, $isAbsolute, $panel, $tenant);
    }

    public static function canAccessNavigation(): bool
    {
        // $user = auth()->user();
        // if (! $user) return false;

        // // Ruolo admin
        // if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
        //     return true;
        // }

        // // Permesso globale (snake_case o StudlyCase:Resource)
        // $perms = [
        //     'ViewAny:Settings',
        //     'View:Settings',
        // ];
        // foreach ($perms as $perm) {
        //     if ($user->can($perm)) {
        //         return true;
        //     }
        // }

        return false;
    }
}
