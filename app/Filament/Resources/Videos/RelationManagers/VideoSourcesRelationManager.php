<?php

namespace App\Filament\Resources\Videos\RelationManagers;

use App\Filament\Resources\Videos\Schemas\VideoSourceForm;
use App\Models\Video;
use App\Models\VideoSource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VideoSourcesRelationManager extends RelationManager
{
    // Carica subito la tabella sotto il form di modifica, senza attendere il
    // caricamento lazy quando la sezione entra nell'area visibile.
    protected static bool $isLazy = false;

    // È il nome del metodo Video::sources(), non il nome della classe né della
    // tabella. Filament lo usa per caricare le versioni del video aperto.
    protected static string $relationship = 'sources';

    protected static ?string $title = 'Versioni';

    // Filament, prima di mostrare un Relation Manager, controlla normalmente
    // la policy del Model collegato (VideoSource). In questo progetto le
    // versioni non sono una risorsa autonoma: possono essere gestite da chi
    // può modificare il Video proprietario.
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof Video
            && Gate::allows('update', $ownerRecord);
    }

    // Definisce i campi usati per inserire una versione. I componenti sono
    // condivisi con la sezione "Prima versione" di CreateVideo.
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(VideoSourceForm::components())
            ->columns(2);
    }

    // Costruisce la tabella delle versioni e le azioni disponibili su di esse.
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Nome')
                    ->placeholder('Senza nome'),

                TextColumn::make('version_date')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('provider')
                    ->label('Tipo')
                    ->badge(),

                TextColumn::make('playback_url')
                    ->label('Link video')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Apri video' : '—')
                    ->url(
                        fn (VideoSource $record): ?string => $record->playback_url,
                        shouldOpenInNewTab: true,
                    )
                    ->placeholder('—'),

                TextColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Apri thumbnail' : '—')
                    ->url(
                        fn (VideoSource $record): ?string => $record->thumbnail_url,
                        shouldOpenInNewTab: true,
                    )
                    ->placeholder('—'),

                IconColumn::make('is_current')
                    ->label('Video corrente')
                    ->boolean(),
            ])
            ->headerActions([
                Action::make('createVersion')
                    ->label('Aggiungi versione')
                    ->icon('heroicon-o-plus')
                    ->schema(VideoSourceForm::components())
                    ->action(function (array $data, Action $action): void {
                        // Owner record è il Video aperto nella pagina edit.
                        /** @var Video $video */
                        $video = $this->getOwnerRecord();

                        Gate::authorize('update', $video);

                        DB::transaction(function () use ($data, $video): void {
                            // Una nuova versione diventa corrente: prima
                            // disattiviamo tutte le altre dello stesso video.
                            $video->sources()->lockForUpdate()->get();
                            $video->sources()->update(['is_current' => false]);

                            $video->sources()->create([
                                ...VideoSourceForm::toDatabase($data),
                                'is_current' => true,
                                'sort_order' => ((int) $video->sources()->max('sort_order')) + 1,
                            ]);
                        });

                        $action->success();
                    })
                    ->successNotificationTitle('Versione aggiunta correttamente'),
            ])
            ->recordActions([
                Action::make('makeCurrent')
                    ->label('Rendi corrente')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (VideoSource $record): bool => ! $record->is_current)
                    ->requiresConfirmation()
                    ->action(function (VideoSource $record, Action $action): void {
                        Gate::authorize('update', $this->getOwnerRecord());

                        // Il Model applica in transazione la regola secondo cui
                        // può esistere una sola versione corrente per video.
                        $record->markAsCurrent();
                        $action->success();
                    })
                    ->successNotificationTitle('Versione impostata come corrente'),

                Action::make('deleteVersion')
                    ->label('Elimina versione')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    // Come nel progetto originale, la versione corrente non si elimina.
                    ->visible(fn (VideoSource $record): bool => ! $record->is_current)
                    ->requiresConfirmation()
                    ->action(function (VideoSource $record, Action $action): void {
                        Gate::authorize('update', $this->getOwnerRecord());

                        // L'azione è nascosta per la versione corrente; qui
                        // viene eliminata solamente la versione selezionata.
                        $record->delete();
                        $action->success();
                    })
                    ->successNotificationTitle('Versione eliminata'),
            ])
            ->defaultSort('version_date', 'desc')
            ->striped();
    }
}
