<?php

namespace App\Filament\Resources\Videos\Tables;

use App\Filament\Resources\VideoSources\VideoSourceResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Video;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
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
            ])
            ->recordActions([
                Action::make('manageVersions')
                    ->label('Modifica')
                    ->icon('heroicon-o-pencil-square')
                    // Apre la Resource delle versioni passando il video come
                    // query string. La pagina ricava da esso il contenitore.
                    ->url(fn (Video $record): string => VideoSourceResource::getUrl('index', [
                        'video' => $record,
                    ])),

                DeleteAction::make(),
            ])
            ->defaultSort('sort_order')
            ->striped();
    }
}
