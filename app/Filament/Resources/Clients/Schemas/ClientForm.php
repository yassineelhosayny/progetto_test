<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    // Riceve lo Schema creato da Filament e gli assegna i componenti del form.
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Raggruppa visivamente i campi dentro una sezione chiamata "Cliente".
            Section::make('Cliente')
                ->schema([
                    // Il nome del componente coincide con la colonna clients.client_name.
                    TextInput::make('client_name')
                        ->label('Nome cliente')
                        // Il form non può essere salvato senza il nome del cliente.
                        ->required()
                        // Rispetta la lunghezza massima della colonna string della migration.
                        ->maxLength(255),

                    // Corrisponde alla colonna clients.slug e rappresenta il testo usato nell'URL.
                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(255)
                        // Impedisce slug duplicati; durante la modifica ignora il record corrente.
                        ->unique('clients', 'slug', ignoreRecord: true)
                        // Il Model normalizza anche il valore manuale: "Agras Demo" diventa "agras-demo".
                        // Se resta vuoto, l'evento saving del Model lo genera da client_name.
                        ->helperText('Puoi inserirlo manualmente: verrà trasformato in formato URL. Se è vuoto, viene generato dal nome del cliente.'),

                    // Corrisponde al booleano clients.is_active.
                    Toggle::make('is_active')
                        ->label('Attivo')
                        // Nei nuovi record il selettore parte attivo, come il default della migration.
                        ->default(true),
                ])
                // Su schermi abbastanza grandi dispone i campi su due colonne.
                ->columns(2)
                // Fa occupare alla sezione tutta la larghezza disponibile nella pagina.
                ->columnSpanFull(),
        ]);
    }
}
