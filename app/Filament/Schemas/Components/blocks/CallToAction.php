<?php

namespace App\Filament\Schemas\Components\blocks;

use Dom\Text;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class CallToAction
{
    public static function make(): Block
    {
        return Block::make('call_to_action')
            ->label('Call to Action')
            ->icon('heroicon-o-megaphone')
            ->schema([
                TextInput::make('title')
                    ->label('Main Title')
                    ->maxLength(255)
                    ->placeholder('Ready to Get Started?')
                    ->required(),

                Textarea::make('content')
                    ->label('Content')
                    ->rows(3)
                    ->maxLength(1000)
                    ->required(),

                TextInput::make('button_text')
                    ->label('Primary Button Text')
                    ->maxLength(50)
                    ->placeholder('Get Started')
                    ->required(),

                TextInput::make('button_url')
                    ->label('Primary Button URL')
                    ->url()
                    ->placeholder('https://example.com')
                    ->required(),

                FileUpload::make('background_image')
                    ->label('Background Image')
                    ->image()
                    ->maxSize(2048)
                    ->directory('blocks/call-to-action')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->previewable(),
            ]);
    }
}