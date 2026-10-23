<?php

namespace App\Filament\Resources\VideoSources;

use App\Filament\Resources\VideoSources\Pages\ListVideoSources;
use App\Filament\Resources\VideoSources\Schemas\VideoSourceForm;
use App\Filament\Resources\VideoSources\Tables\VideoSourcesTable;
use App\Models\VideoSource;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VideoSourceResource extends Resource
{
    // La Resource si apre soltanto dalla lista dei video e non crea una voce
    // separata nel menu laterale di Filament.
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = VideoSource::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $modelLabel = 'Versione video';

    protected static ?string $pluralModelLabel = 'Versioni video';

    // Lo schema è condiviso con la prima versione inserita durante la
    // creazione del Video. In questa Resource viene usato dall'azione modale.
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(VideoSourceForm::components())
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return VideoSourcesTable::configure($table);
    }

    // Per ora serve una sola pagina: lista e creazione modale delle versioni.
    public static function getPages(): array
    {
        return [
            'index' => ListVideoSources::route('/'),
        ];
    }
}
