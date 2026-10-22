<?php

namespace App\Filament\Resources\Videos\Tables;

use App\Filament\Resources\Videos\VideoResource;
use App\Models\Video;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VideosTable
{
    // Configura la lista dei video del contenitore. Il filtro per contenitore
    // non è qui: viene applicato da ListVideos::getTableQuery().
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('videoSubcategory.videoCategory.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('videoSubcategory.name')
                    ->label('Sottocategoria')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currentSource.label')
                    ->label('Versione corrente')
                    ->placeholder('Senza versione'),

                TextColumn::make('currentSource.playback_url')
                    ->label('Link')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Apri video' : '—')
                    ->url(
                        fn (Video $record): ?string => $record->currentSource?->playback_url,
                        shouldOpenInNewTab: true,
                    )
                    ->placeholder('—'),

                IconColumn::make('is_active')
                    ->label('Visibile')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make()
                    // Ricostruisce l'URL con contenitore e video, perché la
                    // route di modifica richiede entrambi i parametri.
                    ->url(fn (Video $record): string => VideoResource::getUrl('edit', [
                        'videoDemo' => $record->videoSubcategory->videoCategory->video_demo_id,
                        'record' => $record,
                    ])),

                DeleteAction::make(),
            ])
            ->defaultSort('sort_order')
            ->striped();
    }
}
