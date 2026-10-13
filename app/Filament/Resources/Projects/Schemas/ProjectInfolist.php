<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informazioni')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('title')
                                ->label('Titolo')
                                ->getStateUsing(fn ($record) => $record->getTranslation('title', 'it', false) ?? ''),

                            TextEntry::make('slug')
                                ->label('Slug')
                                ->copyable(),

                            TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->colors([
                                    'danger' => 'draft',
                                    'success' => 'published',
                                    'info' => 'scheduled',
                                ])
                                ->formatStateUsing(fn ($state) => match ($state) {
                                    'draft' => 'Bozza',
                                    'published' => 'Pubblicato',
                                    'scheduled' => 'Programmato',
                                    default => $state,
                                }),

                            TextEntry::make('published_at')
                                ->label('Pubblicato')
                                ->dateTime('d/m/Y H:i')
                                ->placeholder('Non impostato'),

                            TextEntry::make('public_url')
                                ->label('URL')
                                ->url(fn ($record) => $record->public_url)
                                ->openUrlInNewTab()
                                ->columnSpan(2),
                        ]),
                ]),

            Section::make('Media')
                ->schema([
                    ImageEntry::make('featured_image')
                        ->label('Immagine principale')
                        ->disk('public')
                        ->placeholder('Nessuna immagine'),
                ])
                ->collapsible(),

            Section::make('SEO')
                ->schema([
                    TextEntry::make('meta_title')
                        ->label('Meta titolo')
                        ->getStateUsing(fn ($record) => $record->getTranslation('meta_title', 'it', false) ?? ''),

                    TextEntry::make('meta_description')
                        ->label('Meta descrizione')
                        ->getStateUsing(fn ($record) => $record->getTranslation('meta_description', 'it', false) ?? '')
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(true),
        ]);
    }
}
