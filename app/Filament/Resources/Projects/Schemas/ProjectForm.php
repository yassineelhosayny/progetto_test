<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Helpers\LocaleHelper;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->schema([
                    Section::make('Contenuto')
                        ->schema([
                            Tabs::make('Lingue')
                                ->tabs(LocaleHelper::generateLanguageTabs([
                                    'basic' => [
                                        'title' => [
                                            'required' => true,
                                            'live' => true,
                                        ],
                                        'description' => [],
                                    ],
                                    'content' => [
                                        'content' => [],
                                    ],
                                    'seo' => [
                                        'meta_title' => [],
                                        'meta_description' => [],
                                        'meta_keywords' => [],
                                    ],
                                ])),
                        ])
                        ->columnSpan(2),

                    Section::make('Impostazioni')
                        ->schema([
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique('projects', 'slug', ignoreRecord: true)
                                ->rules(['alpha_dash'])
                                ->helperText('Versione URL-friendly del titolo. Se vuoto viene generato dal titolo italiano.'),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Bozza',
                                    'published' => 'Pubblicato',
                                    'scheduled' => 'Programmato',
                                ])
                                ->default('draft')
                                ->required()
                                ->live(),

                            DateTimePicker::make('published_at')
                                ->label('Data di pubblicazione')
                                ->helperText('Lascia vuoto per pubblicare subito quando lo status e Pubblicato.')
                                ->hidden(fn ($get) => $get('status') !== 'scheduled'),

                            TextInput::make('sort_order')
                                ->label('Ordine')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),

                            FileUpload::make('featured_image')
                                ->label('Immagine principale')
                                ->image()
                                ->maxSize(5120)
                                ->directory('projects/featured')
                                ->disk('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->previewable(),

                            FileUpload::make('gallery')
                                ->label('Gallery')
                                ->multiple()
                                ->image()
                                ->maxSize(5120)
                                ->directory('projects/gallery')
                                ->disk('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->previewable()
                                ->reorderable(),
                        ])
                        ->columnSpan(1),
                ])
                ->columnSpanFull(),
        ]);
    }
}
