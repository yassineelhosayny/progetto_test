<?php

namespace App\Filament\Resources\ResourceGenerators;

use App\Filament\Resources\ResourceGenerators\Pages\CreateResourceGenerator;
use App\Filament\Resources\ResourceGenerators\Pages\EditResourceGenerator;
use App\Filament\Resources\ResourceGenerators\Pages\ListResourceGenerators;
use App\Filament\Resources\ResourceGenerators\Pages\ViewResourceGenerator;
use App\Filament\Resources\ResourceGenerators\Schemas\ResourceGeneratorForm;
use App\Filament\Resources\ResourceGenerators\Schemas\ResourceGeneratorInfolist;
use App\Filament\Resources\ResourceGenerators\Tables\ResourceGeneratorsTable;
use App\Models\ResourceGenerator;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ResourceGeneratorResource extends Resource
{
    protected static string | UnitEnum | null $navigationGroup = 'Contenuti';

    protected static ?int $navigationSort = 4;

    protected static ?string $model = ResourceGenerator::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Crea Risorsa';

    protected static ?string $pluralModelLabel = 'Crea Risorse';

    public static function form(Schema $schema): Schema
    {
        return ResourceGeneratorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourceGeneratorsTable::configure($table);
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
            'index' => ListResourceGenerators::route('/'),
            'create' => CreateResourceGenerator::route('/create'),
            'edit' => EditResourceGenerator::route('/{record}/edit'),
        ];
    }
}
