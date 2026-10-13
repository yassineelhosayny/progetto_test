<?php

namespace App\Filament\Schemas\Components\templates\homepage;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class HeroSlider
{
    public static function make(): Block
    {
        return Block::make('hero_slider')
            ->label('Hero Slider')
            ->icon('heroicon-o-star')
            ->schema([
                Repeater::make('slides')
                    ->label('Slide Hero')
                    ->schema([
                        RichEditor::make('title')
                            ->label('Titolo Slide')
                            ->maxLength(100),
                        
                        FileUpload::make('background_image')
                            ->label('Immagine di Sfondo')
                            ->image()
                            ->maxSize(2048) // 2MB
                            ->directory('pages/hero_slider')
                            ->disk('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'video/mp4'])
                            ->previewable()
                            ->required(),
                    ])
                    ->addActionLabel('Aggiungi Slide')
                    ->reorderableWithButtons()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => isset($state['title']) ? strip_tags($state['title']) : 'Nuova Slide')
                    ->minItems(1)
                    ->maxItems(5)
                    ->defaultItems(1),
            ]);
    }
}