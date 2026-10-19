<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    // Collega questa pagina al ClientResource e quindi al Model Client.
    protected static string $resource = ClientResource::class;

    // Definisce i pulsanti visualizzati nell'intestazione della pagina elenco.
    protected function getHeaderActions(): array
    {
        return [
            // Apre la pagina di creazione di un nuovo cliente.
            CreateAction::make(),
        ];
    }
}
