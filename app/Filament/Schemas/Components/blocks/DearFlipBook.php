<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;

class DearFlipBook
{
    public static function make(): Block
    {
        return Block::make('dear_flip_book')
            ->label('Dear Flipbook Section')
            ->icon('heroicon-o-star')
            ->schema([
                FileUpload::make('pdf_file')
                    ->label('PDF File')
                    ->acceptedFileTypes(['application/pdf'])
                    ->disk('public')
                    ->directory('pages/default/pdfs')
                    ->maxSize(10240) // 10 MB
                    ->helperText('Carica un file PDF da visualizzare come flipbook.'),
            ]);
    }
}