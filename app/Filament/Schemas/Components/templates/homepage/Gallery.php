<?php

namespace App\Filament\Schemas\Components\templates\homepage;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class Gallery
{
    public static function make(): Block
    {
        return Block::make('gallery')
            ->label('Gallery')
            ->icon('heroicon-o-photo')
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3),

                FileUpload::make('images')
                    ->label('Images')
                    ->multiple()
                    ->image()
                    ->maxSize(5120)
                    ->directory('pages/gallery')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->previewable(),
            ]);
    }
}
