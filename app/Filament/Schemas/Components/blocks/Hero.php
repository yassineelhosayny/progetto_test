<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;

class Hero
{
    public static function make(): Block
    {
        return Block::make('hero')
            ->label('Hero Section')
            ->icon('heroicon-o-star')
            ->schema([
                RichEditor::make('title')
                    ->label('Testo')
                    ->maxLength(100),

                Select::make('type')
                    ->label('Tipo Sfondo')
                    ->options([
                        'image' => 'Immagine',
                        'local_video' => 'Video Locale',
                        'embed_video' => 'Video Embed (es.: YouTube/Vimeo)',
                    ])
                    ->live()
                    ->required(),
                
                FileUpload::make('background_image')
                    ->label('Immagine di Sfondo')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->directory('pages/hero')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->previewable()
                     ->visible(fn (Get $get): bool => $get('type') === 'image')
                    ->required(),
                
                FileUpload::make('background_video')
                    ->label('Video di Sfondo')
                    ->maxSize(16384) // 16MB
                    ->directory('pages/hero')
                    ->disk('public')
                    ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov'])
                    ->previewable()
                    ->visible(fn (Get $get): bool => $get('type') === 'local_video')
                    ->required(),

                TextInput::make('embed_url')
                    ->label('URL Video Embed')
                    ->maxLength(255)
                    ->placeholder('https://www.youtube.com/watch?v=example')
                    ->visible(fn (Get $get): bool => $get('type') === 'embed_video')
                    ->required(),
            ]);
    }
}