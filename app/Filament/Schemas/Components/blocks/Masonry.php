<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class Masonry
{
    public static function make(): Block
    {
        return Block::make('masonry')
            ->label('Masonry')
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->maxLength(255)
                    ->placeholder('Gallery title'),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Optional introduction text'),

                Repeater::make('items')
                    ->label('Items')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->maxSize(5120)
                            ->directory('blocks/masonry')
                            ->disk('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->previewable()
                            ->required(),

                        TextInput::make('alt')
                            ->label('Alt Text')
                            ->maxLength(255)
                            ->placeholder('Describe the image'),

                        TextInput::make('caption')
                            ->label('Caption')
                            ->maxLength(255)
                            ->placeholder('Optional caption'),

                        TextInput::make('link_url')
                            ->label('Link URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com'),
                    ])
                    ->addActionLabel('Add Item')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['caption'] ?? $state['alt'] ?? 'New Item')
                    ->minItems(1)
                    ->maxItems(50)
                    ->columns(2),

                Select::make('columns')
                    ->label('Columns')
                    ->options([
                        '2' => '2 Columns',
                        '3' => '3 Columns',
                        '4' => '4 Columns',
                    ])
                    ->default('3'),
            ]);
    }
}
