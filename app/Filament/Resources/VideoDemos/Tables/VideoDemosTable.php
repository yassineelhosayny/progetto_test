<?php

namespace App\Filament\Resources\VideoDemos\Tables;

use App\Models\VideoDemo;
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
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client.client_name')
                    ->label('Cliente')
                    ->placeholder('Nessun cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('categories_count')
                    ->label('Categorie')
                    ->counts('categories')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Attiva')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('requires_password')
                    ->label('Protetta')
                    ->boolean()
                    ->sortable(),

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
