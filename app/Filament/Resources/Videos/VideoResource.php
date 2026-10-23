<?php

namespace App\Filament\Resources\Videos;

use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\Pages\EditVideo;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Filament\Resources\Videos\Schemas\VideoForm;
use App\Filament\Resources\Videos\Tables\VideosTable;
use App\Models\Video;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    // I video si raggiungono soltanto dal pulsante "Gestisci video"
    // del contenitore, quindi non devono creare una seconda voce nel menu.
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = Video::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedPlayCircle;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Video';

    protected static ?string $pluralModelLabel = 'Video';

    // Delega la costruzione del form a VideoForm, così il Resource rimane
    // piccolo e lo stesso schema può essere usato nelle pagine create/edit.
    public static function form(Schema $schema): Schema
    {
        return VideoForm::configure($schema);
    }

    // Delega la configurazione della lista a VideosTable.
    public static function table(Table $table): Table
    {
        return VideosTable::configure($table);
    }

    // Le versioni sono gestite dalla Resource autonoma VideoSourceResource,
    // quindi questo Resource non utilizza Relation Manager.
    public static function getRelations(): array
    {
        return [];
    }

    // Le route restano quelle standard di Filament. L'ID del contenitore non
    // fa parte del percorso: viene passato come query string ?videoDemo=ID.
    public static function getPages(): array
    {
        return [
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }
}
