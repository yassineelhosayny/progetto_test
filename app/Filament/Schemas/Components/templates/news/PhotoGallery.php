<?php

namespace App\Filament\Schemas\Components\templates\news;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Str;

class PhotoGallery
{
    public static function make() : Block
    {
        return Block::make('photo_gallery')
            ->label('Photo Gallery')
            ->schema([
                Repeater::make('gallery')
                    ->label('Gallery')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('')
                            ->image()
                            ->maxSize(2048) // 2MB
                            ->directory('news/gallery')
                            ->disk('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->previewable()
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->itemLabel('Image')
                    ->grid(3)
                    ->collapsible()
            ]);
    }
}