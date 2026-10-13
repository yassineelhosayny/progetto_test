<?php

namespace App\Filament\Resources\ResourceGenerators\Pages;

use App\Filament\Resources\ResourceGenerators\ResourceGeneratorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResourceGenerator extends EditRecord
{
    protected static string $resource = ResourceGeneratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
