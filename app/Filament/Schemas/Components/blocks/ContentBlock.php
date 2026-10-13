<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class ContentBlock
{
    public static function make(): Block
    {
        return Block::make('content_block')
            ->label('Content Block')
            ->icon('heroicon-o-document-text')
            ->schema([
                TextInput::make('pretitle')
                    ->label('Pre-title')
                    ->maxLength(100)
                    ->placeholder('Our Services'),
                
                TextInput::make('title')
                    ->label('Main Title')
                    ->maxLength(255)
                    ->placeholder('What We Offer')
                    ->required(),
                
                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->maxLength(1000)
                    ->placeholder('Describe your content...'),

                Repeater::make('statistics')
                    ->label('Statistics Grid')
                    ->schema([
                        TextInput::make('number')
                            ->label('Number/Value')
                            ->maxLength(20)
                            ->placeholder('100+')
                            ->required(),
                        
                        TextInput::make('label')
                            ->label('Label')
                            ->maxLength(100)
                            ->placeholder('Happy Clients')
                            ->required(),
                        
                        TextInput::make('description')
                            ->label('Description')
                            ->maxLength(200)
                            ->placeholder('Clients served worldwide'),
                    ])
                    ->addActionLabel('Add Statistic')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? 'New Statistic')
                    ->maxItems(6)
                    ->columns(3),

                Repeater::make('buttons')
                    ->label('Call to Action Buttons')
                    ->schema([
                        TextInput::make('title')
                            ->label('Button Text')
                            ->maxLength(50)
                            ->placeholder('Get Started')
                            ->required(),
                        
                        TextInput::make('url')
                            ->label('Button URL')
                            ->url()
                            ->placeholder('https://example.com')
                            ->required(),
                        
                        Select::make('style')
                            ->label('Button Style')
                            ->options([
                                'primary' => 'Primary (Blue)',
                                'secondary' => 'Secondary (Gray)',
                                'outline' => 'Outline',
                                'ghost' => 'Ghost',
                            ])
                            ->default('primary'),
                    ])
                    ->addActionLabel('Add Button')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Button')
                    ->maxItems(3),

                Select::make('text_alignment')
                    ->label('Text Alignment')
                    ->options([
                        'left' => 'Left',
                        'center' => 'Center',
                        'right' => 'Right',
                    ])
                    ->default('center'),

                Select::make('background_color')
                    ->label('Background Color')
                    ->options([
                        'transparent' => 'Transparent',
                        'white' => 'White',
                        'gray-50' => 'Light Gray',
                        'blue-50' => 'Light Blue',
                        'green-50' => 'Light Green',
                    ])
                    ->default('transparent'),
            ]);
    }
}