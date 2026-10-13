<?php

namespace App\Filament\Resources\ResourceGenerators\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ResourceGeneratorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Toggle::make('has_categories')
                    ->label('Has Categories')
                    ->default(false),
            ]);
    }
}
