<?php

namespace App\Filament\Resources\VideoDemos\Pages;

use App\Filament\Resources\VideoDemos\VideoDemoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideoDemos extends ListRecords
{
    protected static string $resource = VideoDemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
