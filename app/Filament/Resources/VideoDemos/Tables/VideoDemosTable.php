<?php

namespace App\Filament\Resources\VideoDemos\Tables;

use App\Filament\Resources\Videos\VideoResource;
use App\Models\VideoDemo;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VideoDemosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.client_name')
                    ->label('Cliente')
                    ->placeholder('Nessun cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Visibile')
                    ->boolean()
                    ->sortable(),

                // Questa non è una colonna del database: Filament calcola il link
                // usando gli slug del cliente e del contenitore.
                TextColumn::make('client_link')
                    ->label('Link Cliente')
                    ->getStateUsing(fn (VideoDemo $record): string => self::makeClientUrl($record))
                    ->url(fn (VideoDemo $record): string => self::makeClientUrl($record), true)
                    ->openUrlInNewTab()
                    ->copyable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Creata il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'client_name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('is_active')
                    ->label('Stato')
                    ->options([
                        '1' => 'Attiva',
                        '0' => 'Non attiva',
                    ]),

                SelectFilter::make('requires_password')
                    ->label('Protezione')
                    ->options([
                        '1' => 'Protetta',
                        '0' => 'Pubblica',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('manageVideos')
                    ->label('Gestisci video')
                    ->icon('heroicon-o-play-circle')
                    ->url(
                        fn (VideoDemo $record): string =>
                            VideoResource::getUrl('index', ['videoDemo' => $record])
                    ),

                // Evita di cancellare per errore l'intera gerarchia collegata alla demo.
                DeleteAction::make()
                    ->visible(fn (VideoDemo $record): bool => ! $record->categories()->exists()),
            ])
            // Filament mostra il comando di riordino e salva automaticamente
            // le nuove posizioni nella colonna sort_order.
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->striped();
    }

    /**
     * Costruisce il futuro indirizzo pubblico senza salvarlo nel database.
     */
    private static function makeClientUrl(VideoDemo $videoDemo): string
    {
        $segments = array_filter([
            'video-demo',
            $videoDemo->client?->slug,
            $videoDemo->slug,
        ]);

        return url(implode('/', $segments));
    }
}
