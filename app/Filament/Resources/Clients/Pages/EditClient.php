<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use App\Models\Client;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    // Indica che questa pagina modifica record gestiti da ClientResource.
    protected static string $resource = ClientResource::class;

    // Definisce i pulsanti visualizzati nell'intestazione della pagina di modifica.
    protected function getHeaderActions(): array
    {
        return [
            // Filament mostra il pulsante Elimina soltanto se il cliente non possiede videodemo.
            // Il controllo evita di proporre un'operazione che la foreign key del database rifiuterebbe.
            DeleteAction::make()
                ->visible(fn (Client $record): bool => ! $record->videoDemos()->exists()),
        ];
    }
}
