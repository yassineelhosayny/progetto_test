<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class PageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1) // Forza una sola colonna
            ->components([
                Section::make('Informazioni')
                    ->schema([
                        TextEntry::make('title')
                            ->getStateUsing(fn($record) => $record->getTranslation('title', 'it') ?? '')
                            ->label('Titolo'),

                        TextEntry::make('status')
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
                                'published' => 'Pubblicata',
                                'scheduled' => 'Programmata',
                            }),

                        TextEntry::make('slug')
                            ->copyable(),

                        TextEntry::make('public_url')
                            ->label('URL')
                            ->url(fn($record) => $record->public_url)
                            ->openUrlInNewTab(),

                        TextEntry::make('published_at')
                            ->label('Pubblicata')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Aggiornata')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columnSpanFull(), // Occupa tutta la larghezza

                Section::make('Live Preview')
                    ->schema([
                        ViewEntry::make('live_preview')
                            ->view('admin.page-live-preview')
                            ->viewData(fn($record) => [
                                'page' => $record,
                                'template' => $record->template ?? 'default'
                            ])
                            ->label('Page Preview')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull() // Occupa tutta la larghezza
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }
}