<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Filament\Resources\Clients\Schemas\ClientForm;
use App\Filament\Resources\Clients\Tables\ClientsTable;
use App\Models\Client;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ClientResource extends Resource
{
    // Raggruppa la voce "Clienti" nella sezione "Video Demo" del menu laterale.
    // Il generatore non la aggiunge perché non può sapere come vogliamo organizzare il menu.
    protected static string | UnitEnum | null $navigationGroup = 'Video Demo';

    // Stabilisce la posizione della voce all'interno del gruppo: i numeri più bassi vengono prima.
    protected static ?int $navigationSort = 1;

    // Indica a Filament quale Model Eloquent deve leggere, creare e modificare.
    protected static ?string $model = Client::class;

    // Icona mostrata accanto alla voce "Clienti" nel menu laterale.
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    // Campo usato per identificare un record nell'interfaccia, per esempio "Natura" invece dell'ID 1.
    protected static ?string $recordTitleAttribute = 'client_name';

    // Testo visualizzato per questa risorsa nel menu laterale.
    protected static ?string $navigationLabel = 'Clienti';

    // Nome singolare usato da Filament nei titoli e nei messaggi, per esempio "Crea Cliente".
    protected static ?string $modelLabel = 'Cliente';

    // Nome plurale usato da Filament, per esempio nella pagina che elenca tutti i clienti.
    protected static ?string $pluralModelLabel = 'Clienti';

    // Delega la configurazione del form al file ClientForm, mantenendo questo Resource ordinato.
    public static function form(Schema $schema): Schema
    {
        return ClientForm::configure($schema);
    }

    // Delega colonne, filtri e azioni della lista al file ClientsTable.
    public static function table(Table $table): Table
    {
        return ClientsTable::configure($table);
    }

    // Qui aggiungeremo eventuali Relation Manager; per ora non ne abbiamo bisogno.
    public static function getRelations(): array
    {
        return [];
    }

    // Registra le pagine e i relativi percorsi interni del Resource.
    public static function getPages(): array
    {
        return [
            // /admin/clients: elenco dei clienti.
            'index' => ListClients::route('/'),
            // /admin/clients/create: form per creare un cliente.
            'create' => CreateClient::route('/create'),
            // {record} viene sostituito con l'ID del cliente da modificare.
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }

    // Mostra nel menu un badge con il numero totale dei clienti presenti nel database.
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }
}
