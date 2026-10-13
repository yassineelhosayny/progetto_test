<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class CardsGrid
{
    public static function make(): Block
    {
        return Block::make('cards_grid')
            ->label('Cards Grid')
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                TextInput::make('section_title')
                    ->label('Section Title')
                    ->maxLength(255)
                    ->placeholder('Our Services'),
                
                Textarea::make('section_description')
                    ->label('Section Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Explore what we have to offer'),

                Repeater::make('cards')
                    ->label('Cards')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Card Image')
                            ->image()
                            ->maxSize(2048)
                            ->directory('blocks/cards')
                            ->disk('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->previewable(),
                        
                        TextInput::make('category')
                            ->label('Category Badge')
                            ->maxLength(50)
                            ->placeholder('Service'),
                        
                        TextInput::make('title')
                            ->label('Card Title')
                            ->maxLength(255)
                            ->placeholder('Card Title')
                            ->required(),
                        
                        Textarea::make('description')
                            ->label('Card Description')
                            ->rows(3)
                            ->maxLength(300)
                            ->placeholder('Card description...'),
                        
                        TextInput::make('url')
                            ->label('Card URL')
                            ->url()
                            ->placeholder('https://example.com'),
                        
                        TextInput::make('button.title')
                            ->label('Button Text')
                            ->maxLength(50)
                            ->placeholder('Read More'),
                        
                        Select::make('button.style')
                            ->label('Button Style')
                            ->options([
                                'primary' => 'Primary',
                                'secondary' => 'Secondary',
                                'outline' => 'Outline',
                                'ghost' => 'Ghost',
                            ])
                            ->default('primary'),
                    ])
                    ->addActionLabel('Add Card')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Card')
                    ->minItems(1)
                    ->maxItems(12)
                    ->columns(2),

                Select::make('grid_columns')
                    ->label('Grid Columns')
                    ->options([
                        '1' => '1 Column',
                        '2' => '2 Columns',
                        '3' => '3 Columns',
                        '4' => '4 Columns',
                    ])
                    ->default('3'),

                Select::make('card_style')
                    ->label('Card Style')
                    ->options([
                        'default' => 'Default (Shadow)',
                        'bordered' => 'Bordered',
                        'elevated' => 'Elevated',
                        'minimal' => 'Minimal',
                    ])
                    ->default('default'),
            ]);
    }
}