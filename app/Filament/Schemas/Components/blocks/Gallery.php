<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class Gallery
{
    public static function make(): Block
    {
        return Block::make('gallery')
            ->label('Image Gallery')
            ->icon('heroicon-o-photo')
            ->schema([
                TextInput::make('title')
                    ->label('Gallery Title')
                    ->maxLength(255)
                    ->placeholder('Our Gallery'),
                
                Textarea::make('description')
                    ->label('Gallery Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Explore our latest work'),

                Repeater::make('images')
                    ->label('Images')
                    ->schema([
                        FileUpload::make('path')
                            ->label('Image')
                            ->image()
                            ->maxSize(5120) // 5MB for gallery images
                            ->directory('blocks/gallery')
                            ->disk('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->previewable()
                            ->required(),
                        
                        TextInput::make('title')
                            ->label('Image Title')
                            ->maxLength(255)
                            ->placeholder('Image title'),
                        
                        TextInput::make('alt')
                            ->label('Alt Text')
                            ->maxLength(255)
                            ->placeholder('Describe the image')
                            ->required(),
                        
                        Textarea::make('description')
                            ->label('Image Description')
                            ->rows(2)
                            ->maxLength(300)
                            ->placeholder('Optional description for modal'),
                    ])
                    ->addActionLabel('Add Image')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? $state['alt'] ?? 'New Image')
                    ->minItems(1)
                    ->maxItems(50)
                    ->columns(3),

                Select::make('grid_columns')
                    ->label('Grid Layout')
                    ->options([
                        '2' => '2 Columns',
                        '3' => '3 Columns',
                        '4' => '4 Columns',
                        '5' => '5 Columns',
                        '6' => '6 Columns',
                    ])
                    ->default('4'),

                Select::make('aspect_ratio')
                    ->label('Image Aspect Ratio')
                    ->options([
                        'square' => 'Square (1:1)',
                        'landscape' => 'Landscape (4:3)',
                        'wide' => 'Wide (16:9)',
                        'portrait' => 'Portrait (3:4)',
                    ])
                    ->default('square'),
            ]);
    }
}