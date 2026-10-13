<?php

namespace App\Filament\Clusters\News\Resources\News\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class NewsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informazioni')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('title.it')
                                ->getStateUsing(fn($record) => $record->getTranslation('title', 'it') ?? '')
                                ->label('Titolo (IT)'),

                            TextEntry::make('title.en')
                                ->getStateUsing(fn($record) => $record->getTranslation('title', 'en') ?? '')
                                ->label('Titolo (EN)'),

                            TextEntry::make('slug')
                                ->label('Slug')
                                ->copyable()
                                ->columnSpan(2),

                            TextEntry::make('excerpt.it')
                                ->label('Sommario (IT)')
                                ->columnSpan(2),

                            TextEntry::make('category.name')
                                ->label('Categoria')
                                ->badge()
                                ->color(function (string $state): string {
                                    switch ($state) {
                                        case 'technology':
                                            return 'info';
                                        case 'business':
                                            return 'success';
                                        case 'sports':
                                            return 'warning';
                                        case 'entertainment':
                                            return 'danger';
                                        default:
                                            return 'gray';
                                    }
                                }),

                            IconEntry::make('featured')
                                ->label('In evidenza')
                                ->boolean(),
                        ]),
                ]),

            Section::make('SEO')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('title')
                                ->label('Titolo')
                                ->getStateUsing(fn($record) => $record->getTranslation('title', 'it', false) ?? 'Nessun titolo'),

                            TextEntry::make('description')
                                ->label('Descrizione')
                                ->getStateUsing(fn($record) => $record->getTranslation('description', 'it', false) ?? 'Nessuna descrizione'),
                        ]),
                ]),

            Section::make('Pubblicazione')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->colors([
                                    'danger' => 'draft',
                                    'success' => 'published',
                                    'info' => 'scheduled',
                                ])
                                ->icons([
                                    'heroicon-o-pencil' => 'draft',
                                    'heroicon-o-eye' => 'published',
                                    'heroicon-o-clock' => 'scheduled',
                                ])
                                ->formatStateUsing(fn($state) => match ($state) {
                                    'draft' => 'Bozza',
                                    'published' => 'Pubblicato',
                                    'scheduled' => 'Programmato',
                                }),

                            TextEntry::make('published_at')
                                ->label('Pubblicato')
                                ->dateTime('d/m/Y H:i')
                                ->placeholder('Not set'),

                            TextEntry::make('slug')
                                ->label('Slug')
                                ->copyable(),
                        ]),
                ]),

            Section::make('Metadata')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Creato')
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('updated_at')
                                ->label('Aggiornato')
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('tags')
                                ->label('Tags')
                                ->badge()
                                ->listWithLineBreaks(false)
                                ->placeholder('No tags'),
                        ]),
                ]),
        ]);
    }
}
