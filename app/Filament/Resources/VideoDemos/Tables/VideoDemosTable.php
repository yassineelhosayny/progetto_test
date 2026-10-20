<?php

namespace App\Filament\Resources\VideoDemos\Tables;

use App\Filament\Resources\VideoDemos\VideoDemoResource;
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

                // Il testo e l'URL sono calcolati dal Model usando gli slug
                // del cliente e del contenitore, come nel progetto originale.
                TextColumn::make('public_url')
                    ->label('Link cliente')
                    ->url(fn (VideoDemo $record): string => $record->public_url, true)
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
                            VideoDemoResource::getUrl('videos', ['record' => $record])
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
}
