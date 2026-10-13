<?php

namespace App\Filament\Schemas\Components\templates\homepage;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;

class Credits
{
    public static function make(): Block
    {
        return Block::make('credits')
            ->label('Credits Section')
            ->icon('heroicon-o-star')
            ->schema([

                FileUpload::make('image')
                    ->label('Immagine Crediti 1')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->directory('pages/credits')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->previewable()
                    ->required(),

                RichEditor::make('title')
                    ->label('Titolo')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'link',
                        'undo',
                        'redo',
                        'h3'
                    ])
                    ->columnSpanFull(),

                RichEditor::make('description')
                    ->label('Descrizione')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'link',
                        'undo',
                        'redo',
                    ])
                    ->columnSpanFull(),

                FileUpload::make('image_2')
                    ->label('Immagine Crediti 2')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->directory('pages/credits')
                    ->disk('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->previewable()
                    ->required(),
            ]);
    }
}