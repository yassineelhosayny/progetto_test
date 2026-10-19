<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    // Indica che questa pagina crea record gestiti da ClientResource.
    protected static string $resource = ClientResource::class;

    // Dopo il salvataggio non mostra il pulsante "Crea e creane un altro".
    protected static bool $canCreateAnother = false;

    // Dopo aver creato il cliente, torna alla pagina che mostra l'elenco dei clienti.
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
