<?php

namespace App\Filament\Clusters\News\Resources\News\Pages;

use App\Filament\Clusters\News\Resources\News\NewsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewNews extends ViewRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
