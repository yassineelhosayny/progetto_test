<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Pages\ViewProject;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Contenuti';

    protected static ?int $navigationSort = 3;

    protected static ?string $model = Project::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Progetti';

    protected static ?string $modelLabel = 'Progetto';

    protected static ?string $pluralModelLabel = 'Progetti';

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function canAccessNavigation(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return true;
        }

        $permissions = [
            'view_any_project',
            'view project',
            'ViewAny:Project',
            'View:Project',
        ];

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }

    public static function getRecordTitle(?Model $record): Htmlable | string | null
    {
        if (! $record) {
            return null;
        }

        if (method_exists($record, 'getTranslation')) {
            $title = $record->getTranslation('title', 'it', false);

            if (! empty($title)) {
                return $title;
            }

            $translations = $record->getTranslations('title');

            if (is_array($translations) && count($translations) > 0) {
                return reset($translations);
            }
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
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
