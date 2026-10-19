<?php

namespace App\Filament\Resources\Clients\Tables;

use App\Models\Client;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientsTable
{
    // Configura come Filament deve mostrare e gestire l'elenco dei Client.
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Mostra il nome del cliente e permette ricerca e ordinamento su client_name.
                TextColumn::make('client_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                // Mostra lo slug, permette di cercarlo e di copiarlo negli appunti.
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    // L'utente può nascondere o mostrare questa colonna dal selettore colonne.
                    ->toggleable(),

                // Colonna calcolata: conta i record della relazione Client::videoDemos().
                TextColumn::make('video_demos_count')
                    ->label('Demo')
                    ->counts('videoDemos')
                    ->sortable(),

                // Rappresenta is_active con un'icona booleana, invece di mostrare 1 oppure 0.
                IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean()
                    ->sortable(),

                // Mostra la data di creazione nel formato italiano giorno/mese/anno e ora.
                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    // La colonna è disponibile, ma inizialmente resta nascosta per alleggerire la tabella.
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Permette di visualizzare solo clienti attivi oppure solo clienti non attivi.
                SelectFilter::make('is_active')
                    ->label('Stato')
                    ->options([
                        '1' => 'Attivo',
                        '0' => 'Non attivo',
                    ]),
            ])
            ->recordActions([
                // Aggiunge a ogni riga l'azione che apre la pagina EditClient.
                EditAction::make(),

                // Mostra Elimina accanto a Modifica solo quando il cliente non ha videodemo.
                // DeleteAction chiede una conferma prima di eliminare definitivamente il record.
                DeleteAction::make()
                    ->visible(fn (Client $record): bool => ! $record->videoDemos()->exists()),
            ])
            // Quando la pagina viene aperta, ordina i clienti alfabeticamente per nome.
            ->defaultSort('client_name')
            // Alterna leggermente il colore delle righe per facilitarne la lettura.
            ->striped();
    }
}
