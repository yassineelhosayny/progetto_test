<?php

namespace App\Filament\Schemas\Components\templates\homepage;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;

class Banner
{
    public static function make(): Block
    {
        return Block::make('Banner')
            ->label('Banner Section')
            ->icon('heroicon-o-star')
            ->schema([
                RichEditor::make('banner_editor')
                    ->label('Editor Banner')
                    ->required()
                    ->maxLength(500)
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'link',
                        'blockquote',
                        'h2',
                        'h3',
                        'undo',
                        'redo',
                    ])
                    ->columnSpanFull(),
                FileUpload::make('banner_image_1')
                    ->label('Immagine Banner 1')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->directory('pages/banner')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->previewable(),
                FileUpload::make('banner_image_2')
                    ->label('Immagine Banner 2')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->directory('pages/banner')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->previewable(),
            ]);
    }
}