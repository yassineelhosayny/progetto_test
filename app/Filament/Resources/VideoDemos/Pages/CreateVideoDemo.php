<?php

namespace App\Filament\Resources\VideoDemos\Pages;

use App\Filament\Resources\VideoDemos\VideoDemoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVideoDemo extends CreateRecord
{
    protected static string $resource = VideoDemoResource::class;

    protected static bool $canCreateAnother = false;

    // Dopo la creazione torna alla lista, come avviene per ClientResource.
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
