<?php

namespace App\Filament\Resources\VideoDemos;

use App\Filament\Resources\VideoDemos\Pages\CreateVideoDemo;
use App\Filament\Resources\VideoDemos\Pages\EditVideoDemo;
use App\Filament\Resources\VideoDemos\Pages\ListVideoDemos;
use App\Filament\Resources\VideoDemos\Schemas\VideoDemoForm;
use App\Filament\Resources\VideoDemos\Tables\VideoDemosTable;
use App\Models\VideoDemo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VideoDemoResource extends Resource
{
    // Inserisce la voce nello stesso gruppo usato dal Resource dei clienti.
    protected static string | UnitEnum | null $navigationGroup = 'Video Demo';

    // La voce viene mostrata dopo "Clienti", che ha ordinamento 1.
    protected static ?int $navigationSort = 2;

    // Model Eloquent gestito dal Resource.
    protected static ?string $model = VideoDemo::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedVideoCamera;

    // Filament usa il titolo della demo per identificare il record nell'interfaccia.
    protected static ?string $recordTitleAttribute = 'title';

    // Nel legacy la voce "Video" apre la lista dei contenitori.
    protected static ?string $navigationLabel = 'Video';

    protected static ?string $modelLabel = 'Contenitore video';

    protected static ?string $pluralModelLabel = 'Contenitori video';

    public static function form(Schema $schema): Schema
    {
        return VideoDemoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoDemosTable::configure($table);
    }

    // I Relation Manager verranno aggiunti solo dopo aver creato i Resource figli.
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideoDemos::route('/'),
            'create' => CreateVideoDemo::route('/create'),
            'edit' => EditVideoDemo::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
