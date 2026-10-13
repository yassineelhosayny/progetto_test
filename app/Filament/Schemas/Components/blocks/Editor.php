<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;

class Editor
{
    public static function make(): Block
    {
        return Block::make('editor')
            ->label('Editor')
            ->icon('heroicon-o-document-text')
            ->schema([
                Repeater::make('editor_repeater')
                    ->label('Editors')
                    ->schema([
                        RichEditor::make('editor_content')
                            ->label('Contenuto personalizzato')
                            ->required()
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
                                'code'
                            ])
                    ])
            ]);
    }
}