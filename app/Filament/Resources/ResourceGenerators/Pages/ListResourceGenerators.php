<?php

namespace App\Filament\Resources\ResourceGenerators\Pages;

use App\Filament\Resources\ResourceGenerators\ResourceGeneratorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResourceGenerators extends ListRecords
{
    protected static string $resource = ResourceGeneratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
