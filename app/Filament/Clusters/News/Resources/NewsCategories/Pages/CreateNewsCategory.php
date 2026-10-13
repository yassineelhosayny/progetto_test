<?php

namespace App\Filament\Clusters\News\Resources\NewsCategories\Pages;

use App\Filament\Clusters\News\Resources\NewsCategories\NewsCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsCategory extends CreateRecord
{
    protected static string $resource = NewsCategoryResource::class;

    protected static bool $canCreateAnother = false;
}
