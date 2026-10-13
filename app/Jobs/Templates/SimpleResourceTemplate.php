<?php

return '<?php

namespace App\\Filament\\Resources\\{{RESOURCE_NAME}};

use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Pages\\Create{{RESOURCE_NAME}};
use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Pages\\Edit{{RESOURCE_NAME}};
use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Pages\\List{{RESOURCE_NAME}};
use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Pages\\View{{RESOURCE_NAME}};
use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Schemas\\{{RESOURCE_NAME}}Form;
use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Schemas\\{{RESOURCE_NAME}}Infolist;
use App\\Filament\\Resources\\{{RESOURCE_NAME}}\\Tables\\{{RESOURCE_NAME}}Table;
use App\\Models\\{{MODEL_NAME}};
use App\\Traits\\TemplateableResource;
use BackedEnum;
use Filament\\Resources\\Resource;
use Filament\\Schemas\\Schema;
use Filament\\Support\\Icons\\Heroicon;
use Filament\\Tables\\Table;
use Filament\\Infolists\\Infolist;

class {{RESOURCE_NAME}}Resource extends Resource
{
    use TemplateableResource;

    protected static ?string $model = {{MODEL_NAME}}::class;

    protected static ?string $modelLabel = \'{{MODEL_LABEL}}\';

    protected static ?string $pluralModelLabel = \'{{PLURAL_MODEL_LABEL}}\';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = \'name\';

    public static function canAccessNavigation(): bool
    {
        $user = auth()->user();
        if (! $user) return false;

        // Ruolo admin
        if ($user->hasRole(\'admin\') || $user->hasRole(\'super_admin\')) {
        // ...existing code...
            return true;
        }

        // Permesso globale (snake_case o StudlyCase:Resource)
        $perms = [
            \'view_any_\' . strtolower(\'{{RESOURCE_NAME}}\'),
            \'view \' . strtolower(\'{{RESOURCE_NAME}}\'),
            \'ViewAny:{{RESOURCE_NAME}}\',
            \'View:{{RESOURCE_NAME}}\',
        ];
        foreach ($perms as $perm) {
            if ($user->can($perm)) {
                // ...existing code...
                return true;
            }
        }

        // Permessi granulari: almeno uno (view o update)
        $hasGranular = $user->getAllPermissions()->pluck(\'name\')->contains(fn($p)=> str_starts_with($p, \'view \' . strtolower(\'{{RESOURCE_NAME}}\')));
        return $hasGranular;
    }

    public static function form(Schema $schema): Schema
    {
        return {{RESOURCE_NAME}}Form::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return {{RESOURCE_NAME}}Table::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return {{RESOURCE_NAME}}Infolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            \'index\' => List{{RESOURCE_NAME}}::route(\'/\'),
            \'create\' => Create{{RESOURCE_NAME}}::route(\'/create\'),
            \'view\' => View{{RESOURCE_NAME}}::route(\'/{record}\'),
            \'edit\' => Edit{{RESOURCE_NAME}}::route(\'/{record}/edit\'),
        ];
    }
}
';