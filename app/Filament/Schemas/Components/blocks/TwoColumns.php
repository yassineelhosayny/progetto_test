<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class TwoColumns
{
    public static function make(): Block
    {
        return Block::make('two_columns')
            ->label('Two Column Layout')
            ->icon('heroicon-o-view-columns')
            ->schema([
                TextInput::make('title')
                    ->label('Section Title')
                    ->maxLength(255)
                    ->placeholder('Enter section title'),
                
                TextInput::make('subtitle')
                    ->label('Subtitle')
                    ->maxLength(255)
                    ->placeholder('Enter subtitle'),

                Textarea::make('content')
                    ->label('Content')
                    ->rows(6)
                    ->placeholder('Enter your content here...')
                    ->maxLength(2000),

                Select::make('layout')
                    ->label('Layout Style')
                    ->options([
                        'image-left' => 'Image Left, Content Right',
                        'image-right' => 'Image Right, Content Left',
                        'content-only' => 'Content Only (No Image)',
                    ])
                    ->default('image-right')
                    ->required(),

                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->maxSize(2048)
                    ->directory('blocks/two-column')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->previewable(),
            ]);
    }
}