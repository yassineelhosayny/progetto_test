<?php

namespace App\Filament\Clusters\News\Resources\News\Pages;

use App\Filament\Clusters\News\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected static bool $canCreateAnother = false;
}
