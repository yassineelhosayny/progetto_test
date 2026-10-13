<?php

namespace App\Filament\Resources\ResourceGenerators\Pages;

use App\Filament\Resources\ResourceGenerators\ResourceGeneratorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResourceGenerator extends CreateRecord
{
    protected static string $resource = ResourceGeneratorResource::class;
}
