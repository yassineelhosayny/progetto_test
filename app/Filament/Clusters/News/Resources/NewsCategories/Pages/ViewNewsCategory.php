<?php

namespace App\Filament\Clusters\News\Resources\NewsCategories\Pages;

use App\Filament\Clusters\News\Resources\NewsCategories\NewsCategoryResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\EditAction;

class ViewNewsCategory extends ViewRecord
{
    protected static string $resource = NewsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
